-- CreateTable
CREATE TABLE "equipamentos" (
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
    "areaId" TEXT NOT NULL,
    CONSTRAINT "equipamentos_areaId_fkey" FOREIGN KEY ("areaId") REFERENCES "areas" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- CreateIndex
CREATE UNIQUE INDEX "equipamentos_numeracao_key" ON "equipamentos"("numeracao");
