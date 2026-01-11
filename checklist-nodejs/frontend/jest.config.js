/** @type {import('jest').Config} */
export default {
  // Ambiente de teste
  testEnvironment: 'jsdom',
  
  // Preset para TypeScript
  preset: 'ts-jest/presets/default-esm',
  
  // Configuração para módulos ES
  extensionsToTreatAsEsm: ['.ts', '.tsx'],
  
  // Transformações
  transform: {
    '^.+\\.(ts|tsx)$': ['ts-jest', {
      useESM: true,
      tsconfig: {
        jsx: 'react-jsx'
      }
    }]
  },
  
  // Padrões de arquivos de teste
  testMatch: [
    '<rootDir>/src/**/*.{test,spec}.{js,ts,tsx}',
    '<rootDir>/src/test/**/*.{test,spec}.{js,ts,tsx}'
  ],
  
  // Arquivos de setup
  setupFilesAfterEnv: ['<rootDir>/src/test/setup.ts'],
  
  // Mapeamento de módulos (aliases)
  moduleNameMapping: {
    '^@/(.*)$': '<rootDir>/src/$1',
    '^@/components/(.*)$': '<rootDir>/src/components/$1',
    '^@/hooks/(.*)$': '<rootDir>/src/hooks/$1',
    '^@/utils/(.*)$': '<rootDir>/src/utils/$1',
    '^@/stores/(.*)$': '<rootDir>/src/stores/$1',
    '^@/services/(.*)$': '<rootDir>/src/services/$1',
    '^@/test/(.*)$': '<rootDir>/src/test/$1'
  },
  
  // Arquivos a serem ignorados
  testPathIgnorePatterns: [
    '<rootDir>/node_modules/',
    '<rootDir>/dist/',
    '<rootDir>/build/'
  ],
  
  // Configuração de cobertura
  collectCoverageFrom: [
    'src/**/*.{ts,tsx}',
    '!src/**/*.d.ts',
    '!src/test/**/*',
    '!src/main.tsx',
    '!src/vite-env.d.ts'
  ],
  
  // Relatórios de cobertura
  coverageReporters: ['text', 'lcov', 'html'],
  
  // Threshold de cobertura
  coverageThreshold: {
    global: {
      branches: 70,
      functions: 70,
      lines: 70,
      statements: 70
    }
  },
  
  // Timeout para testes
  testTimeout: 10000,
  
  // Configurações adicionais
  clearMocks: true,
  restoreMocks: true,
  
  // Suporte para arquivos estáticos
  moduleFileExtensions: ['ts', 'tsx', 'js', 'jsx', 'json'],
  

}