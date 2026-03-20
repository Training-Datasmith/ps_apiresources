# Architecture: ps_apiresources

## Purpose

A PrestaShop module that exposes the PrestaShop domain as a REST API using API Platform.
All endpoints are based on CQRS commands and queries from the PrestaShop Core. Requires
PrestaShop 9.0.3+.

## Directory Structure

```
ps_apiresources.php   # Main module class (Ps_Apiresources extends Module)
src/
  ApiPlatform/        # API Platform resource classes, state providers/processors
  CQRS/              # Command/query handlers bridging API Platform to PS Core CQRS
config/               # Symfony service definitions and API Platform configuration
translations/         # Module translation files
tests/                # PHPStan and unit/integration tests
```

## Key Design Decisions

### API Platform as the HTTP Layer

All HTTP routing, serialization, and OpenAPI documentation is handled by API Platform.
Resource classes in `src/ApiPlatform/` define the endpoints; state providers/processors
translate HTTP operations into PrestaShop CQRS commands and queries.

### CQRS Bridge

No direct database calls occur in API code. All operations go through the PrestaShop
Core's command bus (write) and query bus (read), ensuring business rules and domain events
are always applied.

## Extension Points

- Register additional API Platform resources by adding resource classes to `src/ApiPlatform/`.
- Hook into the PrestaShop command/query bus via decorators for cross-cutting concerns.
