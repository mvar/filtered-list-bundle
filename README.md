MVarFilteredListBundle
===

This bundle helps to quickly create simple lists using Symfony and Doctrine.
This bundle was created with simplicity in mind. It best suites small to medium
projects without superb high needs for performance or features.

Installation
---

TODO: Composer

TODO: AppKernel (must not fail without configuration)

Usage
---

> Examples above pretends you have `Player` entity with fields `id`, `name`, `age` and `sex`.

Configuration example:

```yml
# app/config/config.yml

mvar_filtered_list:
    lists:
        players:
            select: "p"                # Alias of entity
            from: "AppBundle:Player p" # Entity name with alias
```

Such configuration creates list manager service with `mvar_filtered_list.list.players` identifier.

```php
// src/AppBundle/Controller/DefaultController.php

    /**
     * @Route("/list", name="list")
     */
    public function indexAction(Request $request)
    {
        $list = $this->get('mvar_filtered_list.list.players')->handleRequest($request);

        return $this->render(
            'default/index.html.twig',
            [
                'list' => $list,
            ]
        );
    }
```


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
            filters: [ sex, age, pager ]
    filters:
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
