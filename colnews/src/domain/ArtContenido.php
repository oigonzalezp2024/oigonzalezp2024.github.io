<?php

class ArtContenido {
  private string $type;
  private string $data;
  private string $url;
  private string $orientation;

  public function __construct(string $type, string $data, string $url, string $orientation) {
    $this->type = $type;
    $this->data = $data;
    $this->url = $url;
    $this->orientation = $orientation;
  }

  public function setType(string $type) {
    $this->type = $type;
  }

  public function setData(string $data) {
    $this->data = $data;
  }

  public function setUrl(string $url) {
    $this->url = $url;
  }

  public function setOrientation(string $orientation) {
    $this->orientation = $orientation;
  }

  public function getType() {
    return $this->type;
  }

  public function getData() {
    return $this->data;
  }

  public function getUrl() {
    return $this->url;
  }

  public function getOrientation() {
    return $this->orientation;
  }
}

