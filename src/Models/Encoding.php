<?php

namespace Support\Models;

use URL;
use Config;
use Bkwld\Library\Utils\File;
use HtmlObject\Element as HtmlElement;

/**
 * Stores the status of an encoding job and the converted outputs.
 * It was designed to handle the conversion of video files to
 * HTML5 formats with Zencoder but should be abstract enough to
 * support other types of encodings.
 */
class Encoding extends Base
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'outputs' => 'object',
        'response' => 'object',
    ];

    /**
     * Comprehensive list of states
     */
    private static $states = [
        'error',      // Any type of error
        'pending',    // No response from encoder yet
        'queued',     // The encoder API has been hit
        'processing', // Encoding has started
        'complete',   // Encode is finished
        'cancelled',  // The user has canceled the encode
    ];

    /**
     * Polymorphic relationship definition
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function encodable()
    {
        return $this->morphTo();
    }

    /**
     * Return an assoc array for output to JSON when admin asks
     * for progress on an encode
     *
     * @return Facilitador\Models\Encoding
     */
    public function forProgress()
    {
        $this->setVisible(['status', 'message', 'admin_player', 'progress']);
        $this->setAppends(['admin_player', 'progress']);

        return $this;
    }

    /**
     * Set default fields and delete any old encodings for the same source.
     *
     * @return void
     */
    public function onCreating()
    {
        // Delete any other encoding jobs for the same parent and field
        self::where('encodable_type', '=', $this->encodable_type)
            ->where('encodable_id', '=', $this->encodable_id)
            ->where('encodable_attribute', '=', $this->encodable_attribute)
            ->delete();

        // Default values
        $this->status = 'pending';
    }

    /**
     * Once the model is created, try to encode it.  This is done during
     * the created callback so we can we call save() on the record without
     * triggering an infitie loop like can happen if one tries to save while
     * saving
     *
     * @return void
     */
    public function onCreated()
    {
        static::encoder($this)->encode($this->source(), $this->preset);
    }

    /**
     * Delete encoded files that are local to this filesystem
     */
    public function onDeleted()
    {
        // Get the directory of an output
        if (($sources = (array) $this->outputs)      // Convert sources to an array
            && count($sources)                         // If there are sources
            && ($first = array_pop($sources))          // Get the last source
            && preg_match('#^/(?!/)#', $first)         // Make sure it's a local path
            && ($dir = public_path().dirname($first))  // Get the path of the filename
            && is_dir($dir)
        ) {                         // Make sure it's a directory
            File::deleteDir($dir);
        }
    }

    /**
     * Don't log changes since they aren't really the result of admin input
     *
     * @param  string $action
     * @return boolean
     */
    public function shouldLogChange($action)
    {
        return false;
    }

    /**
     * Make an instance of the encoding provider
     *
     * @param  array $input Request::input()
     * @return mixed Response to the API
     */
    public static function notify($input)
    {
        return static::encoder()->handleNotification($input);
    }

    /**
     * Get an instance of the configured encoding provider
     *
     * @param  Facilitador\Models\Encoding
     * @return Support\Template\Input\EncodingProviders\EncodingProvider
     */
    public static function encoder(Encoding $model = null)
    {
        $class = Config::get('sitec.encode.provider');

        return new $class($model);
    }

    /**
     * Get the source video for the encode
     *
     * @return string The path to the video relative to the document root
     */
    public function source()
    {
        $val = $this->encodable->{$this->encodable_attribute};
        if (preg_match('#^http#', $val)) {
            return $val;
        }

        return URL::asset($val);
    }

    /**
     * Store a record of the encoding job
     *
     * @param  string $job_id  A unique id generated by the service
     * @param  mixed  $outputs An optional assoc array where the keys are
     *                         labels for the outputs and the values are
     *                         absolute paths of where the output will be saved
     * @return void
     */
    public function storeJob($job_id, $outputs = null)
    {
        $this->status = 'queued';
        $this->job_id = $job_id;
        $this->outputs = $outputs;
        $this->save();
    }

    /**
     * Update the status of the encode
     *
     * @param  string status
     * @param  string        $message
     * @return void
     */
    public function status($status, $message = null)
    {
        if (!in_array($status, static::$states)) {
            throw new Exception('Unknown state: '.$status);
        }

        // If the current status is complete, don't update again.  I have seen cases of a late
        // processing call on a HLS stream file after it's already been set to complete.  I think
        // it could just be weird internet delays.
        if ($this->complete == 'complete') {
            return;
        }

        // Append messages
        if ($this->message) {
            $this->message .= ' ';
        }

        if ($message) {
            $this->message .= $message;
        }

        // If a job is errored, don't unset it.  Thus, if one output fails, a notification
        // from a later output succeeding still means an overall failure.
        if ($this->status != 'error') {
            $this->status = $status;
        }

        // Save it
        $this->save();
    }

    /**
     * Generate an HTML5 video tag with extra elements for displaying in the admin
     *
     * @return string
     */
    public function getAdminPlayerAttribute()
    {
        return '<div class="player">'
            .$this->getAdminVideoTagAttribute()
            .$this->getAdminStatsMarkupAttribute()
            .'</div>';
    }

    /**
     * Generate an HTML5 video tag with extra elements for displaying in the admin
     *
     * @return string html
     */
    public function getAdminVideoTagAttribute()
    {
        if (!$tag = $this->getTagAttribute()) {
            return;
        }

        $tag->controls();
        if (isset($this->response->output->width)) {
            $tag->width($this->response->output->width);
        }

        return $tag->render();
    }

    /**
     * Get stats as labels with badges
     *
     * @return string html
     */
    protected function getAdminStatsMarkupAttribute()
    {
        if (!$stats = $this->getStatsAttribute()) {
            return '';
        }

        return '<div class="stats">'
            .implode(
                '', array_map(
                    function ($val, $key) {
                        return sprintf(
                            '<span class="label">
                    <span>%s</span>
                    <span class="badge">%s</span>
                        </span>',
                            $key, $val
                        );
                    }, $stats, array_keys($stats)
                )
            )
            .'</div>';
    }

    /**
     * Read an array of stats from the response
     *
     * @return array|void
     */
    protected function getStatsAttribute()
    {
        if (empty($this->response->output)) {
            return;
        }
        $o = $this->response->output;

        return array_filter(
            [
            'Bitrate' => number_format(
                $o->video_bitrate_in_kbps
                + $o->audio_bitrate_in_kbps
            ).' kbps',
            'Filesize' => number_format($o->file_size_in_bytes/1024/1024, 1).' mb',
            'Duration' => number_format($o->duration_in_ms/1000, 1).' s',
            'Dimensions' => number_format($o->width).' x '.number_format($o->height),
            'Download' => '<a href="'.$this->outputs->mp4.'" target="_blank">MP4</a>'
            ]
        );
    }

    /**
     * Get the progress percentage of the encode
     *
     * @return int 0-100
     */
    public function getProgressAttribute()
    {
        switch ($this->status) {
        case 'pending':
            return 0;

        case 'queued':
            return (static::encoder($this)->progress()/100*25) + 25;

        case 'processing':
            return (static::encoder($this)->progress()/100*50) + 50;
        }
    }

    /**
     * Generate an HTML5 video tag via Former's HtmlObject for the outputs
     *
     * @return HtmlObject\Element
     */
    public function getTagAttribute()
    {
        // Require sources and for the encoding to be complete
        if (!$sources = $this->getOutputsValue()) {
            return;
        }

        // Start the tag
        $tag = HtmlElement::video();
        $tag->value('Your browser does not support the video tag. You should <a href="http://whatbrowser.org/">consider updating</a>.');

        // Loop through the outputs and add them as sources
        $types = ['mp4', 'webm', 'ogg', 'playlist'];
        foreach ($sources as $type => $src) {

            // Only allow basic output types
            if (!in_array($type, $types)) {
                continue;
            }

            // Make the source
            $source = HtmlElement::source()->src($src);
            if ($type == 'playlist') {
                $source->type('application/x-mpegurl');
            } else {
                $source->type('video/'.$type);
            }

            // Add a source to the video tag
            $tag->appendChild($source);
        }

        // Return the tag
        return $tag;
    }

    /**
     * Get the outputs value.  I made this to work around an issue where Laravel
     * was double casting the outputs.  I'm not sure why this was happening after
     * some lengthy digging but it seemed unique to Encodings on Elements. So I'm
     * testing whether the attribute value has already been casted.
     *
     * @return object
     */
    public function getOutputsValue()
    {
        if ($this->status != 'complete') {
            return;
        }

        $val = $this->getAttributeFromArray('outputs');
        if (is_string($val)) {
            return $this->outputs;
        }

        return $val;
    }
}
