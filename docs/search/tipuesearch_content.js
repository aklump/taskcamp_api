var tipuesearch = {"pages":[{"title":"Changelog","text":"  All notable changes to this project will be documented in this file.  The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.  [Unreleased]   lorem   [N.N.N] - YYYY-MM-DD  Added   lorem   Changed   lorem   Deprecated   lorem   Removed   lorem   Fixed   lorem   Security   lorem  ","tags":"","url":"CHANGELOG.html"},{"title":"Taskcamp API","text":"    Summary  This is the core API library for Taskcamp, by In the Loft Studios.  It should be used by clients wishing to work with this application.  At the heart, it defines a new serialzation format which is a combination of HTML, YAML, and markdown to represent complicated data relationships, which look like the following.   See this page for more info.  The corresponding MIME type is: application\/prs.taskcamp.entity.  &lt;type property=\"123\"\/&gt; --- attribute: value --- # title  body   PHP  Installation  composer require aklump\/taskcamp_api   Usage  Deserialize  This is what a JSON representation of an \\AKlump\\Taskcamp\\API\\Entity looks like:  {     \"type\": \"bug\",     \"properties\": {         \"projectName\": \"My Project\",         \"projectId\": 123     },     \"data\": {         \"device\": \"mac\",         \"os\": {             \"name\": \"macosx\",             \"version\": \"10.13.6\"         }     },     \"title\": \"The title has too much top margin\",     \"body\": \"\" }   Here is how you would deserialize a JSON string, like the one above.  use AKlump\\Taskcamp\\API\\Entity; use AKlump\\Taskcamp\\API\\EntityEncoder; use AKlump\\Taskcamp\\API\\EntitySerializer;  $serializer = new EntitySerializer(); $entity = $serializer-&gt;deserialize($json, Entity::class, 'json');   If the data was encoded in another format you would use either of these:  $entity = $serializer-&gt;deserialize($entity_markup, Entity::class, EntityEncoder::TYPE); $entity = $serializer-&gt;deserialize($xml, Entity::class, 'xml');   Serialize  Here's how you would serialize an object into EntityEncoder::TYPE format.  $entity = new Entity(); $entity     -&gt;setType('feature')     -&gt;setTitle('Augment the footer with another section')     -&gt;setBody('In order to fit in the about section in the footer, we need to create a fourth column that can take a custom block.  The block needs to be added to the region so the client can edit it.')     -&gt;setProperties(['projectId' =&gt; 123])     -&gt;setData(['prioritye' =&gt; 'high']); $markup = $serializer-&gt;serialize($entity, EntityEncoder::TYPE);   And this is how that format looks:  &lt;feature projectId=\"123\"\/&gt; --- priority: high --- # Augment the footer with another section  In order to fit in the about section in the footer, we need to create a fourth column that can take a custom block.  The block needs to be added to the region so the client can edit it.   Javascript  @todo  Installation  yarn add @aklump\/taskcamp_api   Or if not using yarn:  npm install @aklump\/taskcamp_api  ","tags":"","url":"README.html"},{"title":"Taskcamp MIME Types","text":"  application\/prs.taskcamp.entity  This is used to represent a taskcamp entity (in this case a bug) and looks like this:  &lt;bug projectId=\"0d6c69da-3167-11e9-901e-69eb3dc3eca4\"\/&gt; --- url: 'http:\/\/website.local.loft\/' screen:     width: 1152     height: 2048     colorDepth: 24     pixelDepth: 24     orientation: 270 device: mac --- # asdfsadf  safsafsa    The document consists of three sections: an \"HTML\" open tag, YAML frontmatter, markdown The header MUST be present and MUST be contained on a single line The header MUST be a pseudo-html open tag that defines the type of object, in the example the object is a bug. It should be a self-closing tag. Neither the header nor the entire document use an \"HTML\" closing tag The header MAY have one or more attribute\/values; the values MAY be wrapped in double or single quotes. If an attribute value contains a space, it MUST be wrapper in single or double quotes. Attributes SHOULD be used to convey non-human data about the entity, such as database ids, or related ids. YAML frontmatter should be used to convey data that a human may be interested in viewing. The YAML frontmatter MAY begin with 3 or more - characters, or this line MAY be omitted. The metadata section MAY be present, which is YAML and can be as many lines as you'd like. The YAML frontmatter MUST end with 3 or more - characters. The markdown section MUST contain an top-level title, e.g. # Some Object Title The markdown section MAY contain indefinite additional markdown text.   Relaxed Mode  Relaxed mode allows for less typing.   Header closing is optional. Header properties MUST only be wrapped in quotes when the value contains a space, otherwise quotes MAY be omitted. YAML frontmatter does not need to be preceeded by a --- line.   Here's the above example in relaxed mode:  &lt;bug projectId=0d6c69da-3167-11e9-901e-69eb3dc3eca4&gt; url: 'http:\/\/website.local.loft\/' screen:     width: 1152     height: 2048     colorDepth: 24     pixelDepth: 24     orientation: 270 device: mac --- # asdfsadf  safsafsa   application\/prs.taskcamp.cmd+text  @todo ","tags":"","url":"mime-types.html"},{"title":"Search Results","text":" ","tags":"","url":"search--results.html"}]};
