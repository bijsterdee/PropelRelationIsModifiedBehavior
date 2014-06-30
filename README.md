[![Build Status](https://travis-ci.org/bjdelange/PropelRelationIsModifiedBehavior.svg?branch=master)](https://travis-ci.org/bjdelange/PropelRelationIsModifiedBehavior)

# PropelRelationIsModifiedBehavior
==================================

Extends the isModified method to support checking the related objects for changes, in the way that doSave can save changes to related objects.

## Installation

Add it to your composer.json:

    {
        "require": {
            "bjdelange/propelrelationismodifiedbehavior": "1.0.*"
        }
    }

Add it to Propel's build.properties:

    propel.behavior.relationismodifiedbehavior.class = RelationIsModifiedBehavior

## Usage

In your schema.xml, add the behavior at database or table level where you need it

    <database name="default" defaultIdMethod="native">
        <behavior name="relationismodifiedbehavior" />
        ...

and rebuild your model.

    propel-gen om

The affected table classes will now have an boolean option added to their isModified() methods that allow for
checking related objects in addition to their own fields (which isModified already did).

## Example

The [accompanying tests](src/RelationIsModifiedBehavior/Tests/RelationIsModifiedBehavior) should be more than enough to get started.
