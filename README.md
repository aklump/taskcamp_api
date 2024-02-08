# Taskcamp API

![Taskcamp API](images/taskcamp.jpg)

## Summary

This is the core API library for Taskcamp, by [In the Loft Studios](https://www.intheloftstudios.com).  It should be used by clients wishing to work with this application.

At the heart, it defines a new serialzation format which is a combination of HTML, YAML, and markdown to represent complicated data relationships, which look like the following.   See [this page](@te-mime) for more info.

The corresponding MIME type is: `application/prs.taskcamp.entity`.

    <type property="123"/>
    ---
    attribute: value
    ---
    # title
    
    body

## PHP

### Installation

    composer require aklump/taskcamp_api

### Usage

#### Deserialize

This is what a JSON representation of an `\AKlump\Taskcamp\API\Entity` looks like:

    {
        "type": "bug",
        "properties": {
            "projectName": "My Project",
            "projectId": 123
        },
        "data": {
            "device": "mac",
            "os": {
                "name": "macosx",
                "version": "10.13.6"
            }
        },
        "title": "The title has too much top margin",
        "body": ""
    }
    
Here is how you would deserialize a JSON string, like the one above.

    use AKlump\Taskcamp\API\Entity;
    use AKlump\Taskcamp\API\EntityEncoder;
    use AKlump\Taskcamp\API\EntitySerializer;
    
    $serializer = new EntitySerializer();
    $entity = $serializer->deserialize($json, Entity::class, 'json');
    
If the data was encoded in another format you would use either of these:    

    $entity = $serializer->deserialize($entity_markup, Entity::class, EntityEncoder::TYPE);
    $entity = $serializer->deserialize($xml, Entity::class, 'xml');

#### Serialize

Here's how you would serialize an object into `EntityEncoder::TYPE` format.

    $entity = new Entity();
    $entity
        ->setType('feature')
        ->setTitle('Augment the footer with another section')
        ->setBody('In order to fit in the about section in the footer, we need to create a fourth column that can take a custom block.  The block needs to be added to the region so the client can edit it.')
        ->setProperties(['projectId' => 123])
        ->setData(['prioritye' => 'high']);
    $markup = $serializer->serialize($entity, EntityEncoder::TYPE);
          
And this is how that format looks:

    <feature projectId="123"/>
    ---
    priority: high
    ---
    # Augment the footer with another section
    
    In order to fit in the about section in the footer, we need to create a fourth column that can take a custom block.  The block needs to be added to the region so the client can edit it.

## Javascript

@todo

### Installation

    yarn add @aklump/taskcamp_api

Or if not using _yarn_:

    npm install @aklump/taskcamp_api
