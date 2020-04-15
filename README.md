![Logo](assets/logo.png)

Aggregate definition
--------------------

What is an aggregate?

> Cluster the entities and value objects into aggregates and define boundaries around each. Choose one entity to be the root of each aggregate, and allow external objects to hold references to the root only (references to internal members passed out for use within a single operation only). Define properties and invariants for the aggregate as a whole and give enforcement responsibility to the root or some designated framework mechanism.
> - Eric Evans, Domain-Driven Design Reference: Definitions and Pattern Summaries

List of examples
----------------

This repository contains following examples:

| Example name | Description | Language | Persistence method |
| ------------ | ----------- | :--------:|:------------------:|
| [Availability / Resource](examples/example-availability-resource.md) | Reserving resource based on its availability and other policies | PHP | *not available* |
| [Loan / Loan Application](examples/example-loan-application.md) | Accepting loan application based on attachment verifications | PHP | *not available* |

### Info

Presented implementations are just examples to show some concepts. There is almost always more than one valid solution for given requirements.

Aggregates designing rules
--------------------------

4 rules about designing aggregates by Eric Evans:

- Model true invariants in consistency boundaries
- Design small aggregates
- Reference other aggregates by identity
- Use Eventual Consistency outside the boundary 

Aggregate Design Canvas
-----------------------

Coming soon...

Authors
-------

- [Mariusz Gil](https://twitter.com/mariuszgil)