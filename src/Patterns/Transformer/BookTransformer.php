<?php 
namespace Support\Patterns\Transformer;

use Acme\Model\Book;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'author'
    ];

    // ....

    /**
     * Include Author
     *
     * @param Book $book
     * @return \League\Fractal\Resource\Item
     */
    public function includeAuthor(Book $book)
    {
        $author = $book->author;

        return $this->item($author, new AuthorTransformer);
    }
}