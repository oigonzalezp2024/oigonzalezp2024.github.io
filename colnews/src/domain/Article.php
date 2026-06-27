<?php

class Article
{
  private int $id;
  private string $author;
  private string $date;

  public function __construct(int $id, string $author, string $date)
  {
    $this->id = $id;
    $this->author = $author;
    $this->date = $date;
  }

  /**
   * define el id de articulo
   */
  public function setId(int $id)
  {
    $this->id = $id;
  }
  public function setAuthor(string $author)
  {
    $this->author = $author;
  }
  public function setDate(string $date)
  {
    $this->date = $date;
  }
  
  /**
   * retorna el id de articulo
   */
  public function getId()
  {
    return $this->id;
  }
  public function getAuthor()
  {
    return $this->author;
  }
  public function getDate()
  {
    return $this->date;
  }
}
