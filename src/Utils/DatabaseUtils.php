<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

/**
 * This class provides utility methods to directly interact with the database. It has build in
 * caching to prevent multiple database queries for the same data within one command
 * execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->Database()->……();
 * ```
 */
readonly class DatabaseUtils
{
    public function __construct(
        private DefinitionInstanceRegistry $definitionInstanceRegistry,
    ) {
    }

    public function deleteEntities(string $entity, Criteria $criteria): void
    {
        $repository = $this->definitionInstanceRegistry->getRepository($entity);

        // First load all the ids of the entities
        $ids = $repository->searchIds($criteria, Context::createDefaultContext())->getData();

        // Delete all entities with the IDs
        $repository->delete(
            array_values($ids),
            Context::createDefaultContext(),
        );
    }
}
