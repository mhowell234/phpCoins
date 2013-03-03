#/usr/bin/env python
from utils import DB_DIR, CommonDB, fileToRecord, recordToSQL, writeStatements

# Precious Metals
preciousMetals = fileToRecord('%s/%s/precious_Metal.txt' % (DB_DIR, CommonDB))
preciousMetalsStmts = recordToSQL(preciousMetals, None, None, [{'field':'NULL','type':'int'}, {'field':'NULL','type':'int'}])
writeStatements(preciousMetalsStmts, '%s.sql' % preciousMetals['tableName'], CommonDB)
