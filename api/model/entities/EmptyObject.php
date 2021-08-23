<?php

// Use when need to encode map to JSON, but it's empty.
// json_encode(array())             => [] - array, not map
// json_encode(new EmptyObject())   => {} - map
class EmptyObject {}