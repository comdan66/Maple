<?php

abstract class ApiController extends Controller {
  public function __construct() {
    ifApiError(function() {
      return ['messages' => func_get_args()];
    });
  }
}
