<!--
id: mime
tags: ''
-->

# Taskcamp MIME Types

## application/prs.taskcamp.entity

This is used to represent a taskcamp entity (in this case a bug) and looks like this:

```
<bug projectId="0d6c69da-3167-11e9-901e-69eb3dc3eca4"/>
---
url: 'http://website.local.loft/'
screen:
    width: 1152
    height: 2048
    colorDepth: 24
    pixelDepth: 24
    orientation: 270
device: mac
---
# asdfsadf

safsafsa
```

* The document consists of three sections: a self-closing XML element, YAML data, and markdown content
* These three sections will be referred to as: _element_, _data_ and _content_

### Element

* The element MUST be present and MUST be contained on a single line
* The element MUST be a pseudo-HTML open tag that defines the type of object, in the example the object is a `bug`. It should be a self-closing tag. We say pseudo because it
* Neither the element nor the entire document use an "HTML" closing tag
* The element MAY have one or more attribute/values; the values MAY be wrapped in double or single quotes. If an attribute value contains a space, it MUST be wrapper in single or double quotes.
* Attributes SHOULD be used to convey non-human data about the entity, such as database ids, or related ids. YAML frontmatter should be used to convey data that a human may be interested in viewing.

### Data

* This OPTIONAL section is a YAML mapping of key/value pairs.
* If any data is present then this section must be followed by a separator line of 3 or more dashes, `---`
* This section may be preceded by a separator line of 3 or more dashes.
* If there is no data, then no separator lines are required.

### Content

* The markdown section MUST contain an top-level title, e.g. `# Some Object Title`
* The markdown section MAY contain indefinite additional markdown text.

### Relaxed Mode

Relaxed mode allows for less typing.

* Header closing is optional.
* Header properties MUST only be wrapped in quotes when the value contains a space, otherwise quotes MAY be omitted.
* YAML frontmatter does not need to be preceeded by a `---` line.

Here's the above example in relaxed mode:

```
<bug projectId=0d6c69da-3167-11e9-901e-69eb3dc3eca4>
url: 'http://website.local.loft/'
screen:
    width: 1152
    height: 2048
    colorDepth: 24
    pixelDepth: 24
    orientation: 270
device: mac
---
# asdfsadf

safsafsa
```

## application/prs.taskcamp.cmd+text

@todo
