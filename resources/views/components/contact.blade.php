<div class="contact-form">
    {{ Form::open(array('action' => 'PagesController@contact', 'role' => 'form', 'id' => 'contact-form')) }}
        <div class="row">
            <div class="form-group col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                <input type="text" name="name" class="form-control" id="first-name" placeholder="{{ trans('homepage.contact_name') }}" required="required">
            </div>
            <div class="form-group col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('homepage.contact_email') }}" required="required">
            </div>
            <div class="form-group col-md-12 wow fadeInUp" data-wow-delay="0.6s">
                <textarea rows="6" name="message" class="form-control" id="description" placeholder="{{ trans('homepage.contact_message') }}" required="required"></textarea>
            </div>
            <div class="col-md-12 text-center wow fadeInUp" data-wow-delay="0.8s">
                <div class="actions">
                    <input type="submit" value="{{ trans('homepage.contact_submit') }}" name="submit" id="submitButton" class="btn btn-lg btn-contact-bg" title="{{ trans('homepage.contact_submit') }}">
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>