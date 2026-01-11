-- RedefineTables
PRAGMA defer_foreign_keys=ON;
PRAGMA foreign_keys=OFF;
CREATE TABLE "new_equipamentos" (
    "id" TEXT NOT NULL PRIMARY KEY,
    "numeracao" TEXT NOT NULL,
    "nome" TEXT NOT NULL,
    "tipo" TEXT,
    "fabricante" TEXT,
    "modelo" TEXT,
    "numeroSerie" TEXT,
    "status" TEXT NOT NULL DEFAULT 'ATIVO',
    "prioridade" INTEGER NOT NULL DEFAULT 3,
    "observacoes" TEXT,
    "createdAt" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updatedAt" DATETIME NOT NULL,
    "areaId" TEXT,
    CONSTRAINT "equipamentos_areaId_fkey" FOREIGN KEY ("areaId") REFERENCES "areas" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO "new_equipamentos" ("areaId", "createdAt", "fabricante", "id", "modelo", "nome", "numeracao", "numeroSerie", "observacoes", "prioridade", "status", "tipo", "updatedAt") SELECT "areaId", "createdAt", "fabricante", "id", "modelo", "nome", "numeracao", "numeroSerie", "observacoes", "prioridade", "status", "tipo", "updatedAt" FROM "equipamentos";
DROP TABLE "equipamentos";
ALTER TABLE "new_equipamentos" RENAME TO "equipamentos";
CREATE UNIQUE INDEX "equipamentos_numeracao_key" ON "equipamentos"("numeracao");
PRAGMA foreign_keys=ON;
PRAGMA defer_foreign_keys=OFF;
