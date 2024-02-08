<!--
id: te-mime
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

* The document consists of three sections: an "HTML" open tag, YAML frontmatter, markdown
* The header MUST be present and MUST be contained on a single line
* The header MUST be a pseudo-html open tag that defines the type of object, in the example the object is a `bug`. It should be a self-closing tag.
* Neither the header nor the entire document use an "HTML" closing tag
* The header MAY have one or more attribute/values; the values MAY be wrapped in double or single quotes. If an attribute value contains a space, it MUST be wrapper in single or double quotes.
* Attributes SHOULD be used to convey non-human data about the entity, such as database ids, or related ids. YAML frontmatter should be used to convey data that a human may be interested in viewing.
* The YAML frontmatter MAY begin with 3 or more `-` characters, or this line MAY be omitted.
* The metadata section MAY be present, which is YAML and can be as many lines as you'd like.
* The YAML frontmatter MUST end with 3 or more `-` characters.
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
