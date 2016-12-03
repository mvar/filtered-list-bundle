MVarFilteredListBundle
===

[![Build Status](https://travis-ci.org/mvar/filtered-list-bundle.svg?branch=master)](https://travis-ci.org/mvar/filtered-list-bundle)

This bundle helps to quickly create simple lists using Symfony and Doctrine ORM.
This bundle was created with simplicity in mind. It best suites small to medium
size projects without superb high needs for performance or features.

Installation
---

First, download the Bundle.

Open a command console, enter your project directory and execute the following
command to download the latest stable version of this bundle:

```bash
$ composer require mvar/filtered-list-bundle
```

> This command requires you to have Composer installed globally, as explained in
> the [installation chapter][1] of the Composer documentation.

Now enable the bundle by registering it in `app/AppKernel.php`:

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            // ...
            new MVar\FilteredListBundle\MVarFilteredListBundle(), 
        ];
    }

    // ...
}
```

That's it about installation. Now jump to the next chapter to get know how to use this bundle.

Usage
---

> Examples above pretends you have `Player` entity with fields `id`, `name`, `age` and `sex`.

Configuration example:

```yml
# app/config/config.yml

mvar_filtered_list:
    lists:
        players:
            select: "p"                # DQL snippet for SELECT part (i.e., alias of entity)
            from: "AppBundle:Player p" # DQL snippet for FROM part (i.e., entity name with alias)
```

Such configuration creates list manager service with `mvar_filtered_list.list.players` identifier.

Now lets add controller's method where we generate player list and pass it to the
template which we will create next:

```php
// src/AppBundle/Controller/DefaultController.php

    /**
     * @Route("/list", name="list")
     */
    public function listAction(Request $request)
    {
        $list = $this->get('mvar_filtered_list.list.players')->handleRequest($request);

        return $this->render(
            'default/list.html.twig',
            [
                'list' => $list,
            ]
        );
    }
```

List template:

```twig
{# app/Resources/views/default/list.html.twig #}

{% extends 'base.html.twig' %}

{% block body %}
    <ul>
    {% for player in list %}
        <li>{{ player.name }} ({{ player.age }})</li>
    {% else %}
        <li>No players found!</li>
    {% endfor %}
    </ul>
{% endblock %}
```

This simple example shows how to configure and use a list. The result this page
prints is a list of player names followed by player age. 

If you want to add a pagination, or sorting, or to filter results by any field, follow next chapter.

Using Filters
---

Configuration example:

```yml
# app/config/config.yml

mvar_filtered_list:
    lists:
        players:
            select: "p"
            from: "AppBundle:Player p"
            filters: [ name, sex, age, pager ]
    filters:     
        match:
            name: p.name
        choice:
            sex:
                field: p.sex
                choices:
                    m: Male
                    f: Female
        range:
            age: p.age
        pager:
            pager: ~
```

TODO

[1]: https://getcomposer.org/doc/00-intro.md
