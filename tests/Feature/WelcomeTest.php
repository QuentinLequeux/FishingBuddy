<?php

test('guest can see welcome page', function () {
    $this->get(route('home'))->assertOk();
});
