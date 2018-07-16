<?php

cart()->clear();
cart()->save();
system_message(elgg_echo('cart:empty:success'));
