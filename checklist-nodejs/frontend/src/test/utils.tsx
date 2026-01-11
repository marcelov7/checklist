import * as React from 'react'
import { render, RenderOptions } from '@testing-library/react'
import { BrowserRouter } from 'react-router-dom'
import { renderHook, RenderHookOptions } from '@testing-library/react'

// Wrapper customizado para testes com React Router
const AllTheProviders = ({ children }: { children: React.ReactNode }) => {
  return (
    <BrowserRouter>
      {children}
    </BrowserRouter>
  )
}

// Função de render customizada
const customRender = (
  ui: React.ReactElement,
  options?: Omit<RenderOptions, 'wrapper'>
) => render(ui, { wrapper: AllTheProviders, ...options })

// Função para renderizar hooks
const customRenderHook = <TProps, TResult>(
  callback: (props: TProps) => TResult,
  options?: RenderHookOptions<TProps>
) => renderHook(callback, { wrapper: AllTheProviders, ...options })

// Mock de dados para testes
export const createMockUser = (overrides = {}) => ({
  id: 1,
  name: 'Test User',
  email: 'test@example.com',
  ...overrides
})

export const createMockArea = (overrides = {}) => ({
  id: 1,
  nome: 'Test Area',
  descricao: 'Test Description',
  ...overrides
})

export const createMockStop = (overrides = {}) => ({
  id: 1,
  nome: 'Test Stop',
  descricao: 'Test Stop Description',
  area_id: 1,
  ...overrides
})

// Mock de performance entry
export const createMockPerformanceEntry = (overrides = {}) => ({
  name: 'test-measure',
  entryType: 'measure',
  startTime: 100,
  duration: 50,
  ...overrides
})

// Função de delay para testes assíncronos
export const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms))

// Mocks globais para testes
export const createMockIntersectionObserver = () => jest.fn().mockImplementation(() => ({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn()
}))

export const mockIntersectionObserver = jest.fn().mockImplementation(() => ({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn()
}))

export const mockResizeObserver = jest.fn().mockImplementation(() => ({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn()
}))

export const mockLocalStorage = {
  getItem: jest.fn(),
  setItem: jest.fn(),
  removeItem: jest.fn(),
  clear: jest.fn()
}

export const mockPerformanceNow = jest.fn(() => Date.now())

// Re-exportar tudo do testing-library
export * from '@testing-library/react'
export { customRender as render, customRenderHook as renderHook }