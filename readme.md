# Nette & Flow
Example of **Cooperative Multitasking** components on a single site served by Nette Framework.
The components need to process several network requests to render themselves, which is normally slow.

It uses *yield* (PHP 5.5) to switch between tasks, *Flow* as scheduler and *Rect* as parallel http client.


More in this [blog post on gist](https://gist.github.com/juzna/7194037)
