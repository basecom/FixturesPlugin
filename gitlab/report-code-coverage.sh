rdom () { local IFS=\> ; read -d \< E C ;}
regex="classes count=\"[0-9]+\" tested=\"[0-9]+\" percent=\"([0-9]+).[0-9]+\""
CODE_COVERAGE=""
PROJECT_DIR=$( dirname "${BASH_SOURCE[0]}" )/..

while rdom; do
    if [[ $E =~ $regex ]]; then
        CODE_COVERAGE=${BASH_REMATCH[1]}
        CODE_COVERAGE=$(($CODE_COVERAGE+0))
        break
    fi
done < ${PROJECT_DIR}/coverage/phpspec-coverage-xml/index.xml

if [[ "${CODE_COVERAGE}" == "" ]]; then
    echo "Warning: No code coverage available!"

    exit
fi

if [[ $CODE_COVERAGE -ge $CODE_COVERAGE_MIN_PERCENTAGE ]]; then
    echo ""
    echo "=================================================================="
    echo "Code Coverage: ${CODE_COVERAGE}% (min: ${CODE_COVERAGE_MIN_PERCENTAGE}%)"
    echo "=================================================================="
    echo ""

    exit
else
    echo ""
    echo "=================================================================="
    echo "Code Coverage too low!"
    echo "Code Coverage: ${CODE_COVERAGE}% (min: ${CODE_COVERAGE_MIN_PERCENTAGE}%)"
    echo "=================================================================="
    echo ""

    exit 1
fi
