#!/bin/sh

python makeSchema.py

mysql -u root -proot < ../SCHEMA.sql

rm ../SCHEMA.sql
rm -rf ../sqlImport

python convertDataToSQL.py

python makeSQL.py

mysql -u root -proot < ../SQL.sql
rm ../SQL.sql

./downloadData.sh

python formatData.py


python coinPhotosSQL.py
python ourCoinsSQL.py
python preciousMetalsSQL.py

echo "USE UsCoinDB" > coin.sql
cat ../sqlImport/UsCoinDB/CoinPhoto.sql >> coin.sql
echo "USE UsCoinDB" >> coin.sql
cat ../sqlImport/UsCoinDB/CoinThumbnail.sql >> coin.sql
echo "USE OurCoinDB" >> coin.sql
cat ../sqlImport/OurCoinDB/OurCoin.sql >> coin.sql
echo "USE CommonDB" >> coin.sql
cat ../sqlImport/CommonDB/PreciousMetal.sql >> coin.sql

mysql -u root -proot < coin.sql
rm coin.sql

./downloadSecondaryData.sh

python formatSecondaryData.py
python ourCoinsPhotosSQL.py
python ourCoinsRatingsSQL.py

echo "USE OurCoinDB\n;" > ourCoin.sql
cat ../sqlImport/OurCoinDB/OurCoinPhoto.sql >> ourCoin.sql
echo "USE OurCoinDB\n;" >> ourCoin.sql
cat ../sqlImport/OurCoinDB/OurCoinThumbnail.sql >> ourCoin.sql
cat ../sqlImport/OurCoinDB/Rating.sql >> ourCoin.sql
mysql -u root -proot < ourCoin.sql
rm ourCoin.sql


python coinMetalCompositionSQL.py

echo "USE UsCoinDB;\n" > metal.sql
cat ../sqlImport/UsCoinDB/MintCoinAttribute.sql >> metal.sql
cat ../sqlImport/UsCoinDB/CoinMetalComposition.sql >> metal.sql
mysql -u root -proot < metal.sql
rm metal.sql

./updateMetalValues.sh

python convertForeignDataToSQL.py

python makeForeignSQL.py

mysql -u root -proot < ../FOREIGN_SCHEMA.sql

rm ../FOREIGN_SCHEMA.sql


./downloadForeignData.sh

python formatForeignData.py

python foreignCoinPhotosSQL.py

echo "USE ForeignCoinDB\n;" > foreignCoin.sql
cat ../sqlImport/ForeignCoinDB/CoinPhoto.sql >> foreignCoin.sql
echo "USE ForeignCoinDB\n;" >> foreignCoin.sql
cat ../sqlImport/ForeignCoinDB/CoinThumbnail.sql >> foreignCoin.sql
mysql -u root -proot < foreignCoin.sql
rm foreignCoin.sql

curl -o /dev/null http://localhost:8888/batch/addSearchableData.php


python convertTrackerDataToSQL.py

python makeTrackerSQL.py

mysql -u root -proot < ../TRACKER_SCHEMA.sql

rm ../TRACKER_SCHEMA.sql


