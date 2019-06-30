<?php

Route::view('/{vue_capture?}', 'index')->where('vue_capture', '[\/\w\.-]*');
