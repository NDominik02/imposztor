<?php
require_once "jsonstorage.php";

class Post
{
    public $title;
    public $text;
    public $type;
    public $image;

    public function __construct($title = null, $text = null, $type = null, $image = null)
    {
        
        $this->title = $title;
        $this->text = $text;
        $this->type = $type;
        $this->image = $image;
        }

    public static function from_array(array $arr): Post
    {
        $instance = new Post();
        $instance->title = $arr['title'] ?? null;
        $instance->text = $arr['text'] ?? null;
        $instance->type = $arr['type'] ?? null;
        $instance->image = $arr['image'] ?? null;
        return $instance;
    }

    public static function from_object(object $obj): Post
    {
        return self::from_array((array) $obj);
    }
}

class PostRepository
{
    private $storage;
    public function __construct()
    {
        $this->storage = new JsonStorage('data/posts.json');
    }
    private function convert(array $arr): array
    {
        return array_map([Post::class, 'from_object'], $arr);
    }
    public function all()
    {
        return $this->convert($this->storage->all());
    }
    public function add(Post $post): string
    {
        return $this->storage->insert($post);
    }   
    public function remove(string $key): void 
    {
        $this->storage->delete($key);
    }
    public function getPostById(string $postId = null): array
    {
        return $this->convert($this->storage->filter(function ($post) use ($postId){
            return $post === $postId;
        }));
    }
}