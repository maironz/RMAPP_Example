<?php
// ControllerInterface.php
namespace RMapp\src;
use RMapp\Helpers\LangHelper;

interface ControllerInterface{
  public function setLanguage(LangHelper $php,LangHelper $js): void;
  public function render() : void;
}