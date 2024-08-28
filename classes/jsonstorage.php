<?php
include_once "jsonio.php";

class JsonStorage
{
    private $io;
    private $filename;
    public function __construct(string $filename)
    {
        $this->io = new JsonIO();
        $this->filename = $filename;
    }
    public function all()
    {
        return (array) $this->io->load_from_file($this->filename, false);
    }
    public function insert(object $item): string
    {
        $all = $this->all();
        if($_SESSION["user"] != "admin")
        {            
            $id = uniqid('', true);
            $item->_id = $id;
        }
        else{
            if(isset($_SESSION["PostToModify_id"]))
            {
                $id = $_SESSION["PostToModify_id"];
            }
            else{
                $id = 'post' . count($all);
            }
        }
        $all[$id] = $item;
        $this->io->save_to_file($this->filename, $all);
        return $id;
    }
    public function filter(callable $fn)
    {
        $all = $this->all();
        return array_filter($all, $fn);
    }
    public function update(callable $filter, callable $updater)
    {
        $all = $this->all();
        array_walk($all, function (&$item) use ($filter, $updater) {
            if ($filter($item)) {
                $updater($item);
            }
        });
        $this->io->save_to_file($this->filename, $all);
    }
    public function delete(string $key)
    {
        $all = $this->all();
        if(isset($all[$key]))
        {
            unset($all[$key]);
            $this->io->save_to_file($this->filename, $all);
        }

    }
}