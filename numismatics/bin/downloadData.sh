#!/bin/sh

rm ../data/*

curl -o ../data/coinValueReference http://localhost:8888/output/coinValueReference.php

curl -o ../data/coinReference http://localhost:8888/output/coinReference.php

curl -o ../data/coinYearReference http://localhost:8888/output/coinYearReference.php

curl -o ../data/mintReference http://localhost:8888/output/mintReference.php

curl -o ../data/mintCoinReference http://localhost:8888/output/mintCoinReference.php

curl -o ../data/ratingCategoryReference http://localhost:8888/output/ratingCategoryReference.php

curl -o ../data/ratingValueReference http://localhost:8888/output/ratingValueReference.php

curl -o ../data/ratingAgencyReference http://localhost:8888/output/ratingAgencyReference.php

curl -o ../data/mintCoinValueReference http://localhost:8888/output/mintCoinValueReference.php

curl -o ../data/coinGradeCategoryReference http://localhost:8888/output/coinGradeCategoryReference.php

curl -o ../data/coinGradeReference http://localhost:8888/output/coinGradeReference.php

curl -o ../data/foreignCountryReference http://localhost:8888/output/foreignCountryReference.php
