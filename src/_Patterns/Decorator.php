<?php
/**
 * Decorator Pattern
The decorator pattern can facilitate the rendering of a business model bean in the context of the MVC pattern. The decorator wraps or masks the bean and can present the bean in a certain way for a particular GUI component.

The pattern deals with ‘decorating’ a business model bean with one or more decorators. The decorators can add, remove, enhance or modify properties of the bean they are masking. This reduces work that a renderer (the View) might need to do.

Examples what a decorator might do :

Modify the formatting of a date.
Derive an extra field. Eg: Age from date of birth
Hide unnecessary fields (useful when doing data binding on, for example, a table)
The decorator pattern can also be used to enhance GUI components (in the context of painting over), although I won’t be talking about that here.

The classic approach is to have an interface defined like this :
 */