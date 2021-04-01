#!/usr/bin/env bash

set -e ; # Have script exit in the event of a failed command.

if [[ "${FORCE_ALL_MUTATIONS}" == "true" ]]; then
    INFECTION_FILTER="";
else
    git remote set-branches --add origin ${BASE_BRANCH};
    git fetch;
    CHANGED_FILES=$(git diff origin/${BASE_BRANCH} --diff-filter=AM --name-only | grep src/ | paste -sd "," -);

    if [[ -z "${CHANGED_FILES}" ]]; then
        # if there are no changed files there is no need to run infection
        echo "no changed files";
        phpdbg -qrr vendor/bin/phpspec --config=phpspec_coverage.yml run
        exit 0;
    fi

    INFECTION_FILTER="--filter=${CHANGED_FILES} --ignore-msi-with-no-mutations";

    echo "CHANGED_FILES=${CHANGED_FILES}";
fi

INFECTION_THREAD_COUNT=4

phpdbg -qrr vendor/bin/phpspec --config=phpspec_coverage.yml run
vendor/bin/infection --test-framework=phpspec --coverage=coverage --only-covered --min-msi=100  --threads=${INFECTION_THREAD_COUNT} --show-mutations ${INFECTION_FILTER}
