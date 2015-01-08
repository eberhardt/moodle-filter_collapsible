moodle-filter_collapsible
=========================

Easy way to create collapsible regions within descriptions and text for Moodle.

Also available in the [Moodle plugins directory](https://moodle.org/plugins/view.php?plugin=filter_collapsible).

Usage
=====

To start or end a collapsible region use the keyword '{collapsible}'.
If the starting tag is followed by squared brackets the text within these brackets is shown instead of a general "Click-to-expand"-message.

Example
=======

For instance

```
{collapsible}[read more...]
Your text/HTML here.
{collapsible}
```

will result in following structure:

```
read more... >
+----------------------+
| Your text/HTML here. |
+----------------------+
```

Please see the screenshots on the Moodle project page for visual examples.
