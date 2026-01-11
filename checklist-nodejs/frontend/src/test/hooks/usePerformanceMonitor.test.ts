import { renderHook, act } from '@testing-library/react'
import { usePerformanceMonitor } from '../../utils/hooks-simple.tsx';

describe('usePerformanceMonitor', () => {
  let performanceNowSpy: jest.SpyInstance

  beforeEach(() => {
    // Garantir que performance.now existe no ambiente de teste
    if (!global.performance) {
      global.performance = {} as Performance
    }
    if (!global.performance.now) {
      global.performance.now = jest.fn()
    }
    performanceNowSpy = jest.spyOn(global.performance, 'now').mockReturnValue(0)
  })

  afterEach(() => {
    performanceNowSpy.mockRestore()
  })

  it('should return initial state', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    expect(result.current.metrics).toEqual({})
    expect(typeof result.current.startMeasure).toBe('function')
    expect(typeof result.current.endMeasure).toBe('function')
    expect(typeof result.current.clearMetrics).toBe('function')
  })

  it('should start and end measurements correctly', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    // Simula tempo inicial
    performanceNowSpy.mockReturnValue(1000)
    
    act(() => {
      result.current.startMeasure('test-operation')
    })
    
    // Simula tempo final
    performanceNowSpy.mockReturnValue(1500)
    
    act(() => {
      result.current.endMeasure('test-operation')
    })
    
    expect(result.current.metrics['test-operation'].duration).toBe(500)
  })

  it('should handle multiple measurements', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    // Primeira medição
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure('operation1')
    })
    
    performanceNowSpy.mockReturnValue(1200)
    act(() => {
      result.current.endMeasure('operation1')
    })
    
    // Segunda medição
    performanceNowSpy.mockReturnValue(2000)
    act(() => {
      result.current.startMeasure('operation2')
    })
    
    performanceNowSpy.mockReturnValue(2300)
    act(() => {
      result.current.endMeasure('operation2')
    })
    
    expect(result.current.metrics).toEqual({
      'operation1': { duration: 200, startTime: 1000, endTime: 1200 },
      'operation2': { duration: 300, startTime: 2000, endTime: 2300 }
    })
  })

  it('should handle concurrent measurements', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    // Inicia duas medições
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure('concurrent1')
    })
    
    performanceNowSpy.mockReturnValue(1100)
    act(() => {
      result.current.startMeasure('concurrent2')
    })
    
    // Finaliza em ordem diferente
    performanceNowSpy.mockReturnValue(1500)
    act(() => {
      result.current.endMeasure('concurrent1')
    })
    
    performanceNowSpy.mockReturnValue(1800)
    act(() => {
      result.current.endMeasure('concurrent2')
    })
    
    expect(result.current.metrics).toEqual({
      'concurrent1': { duration: 500, startTime: 1000, endTime: 1500 }, // 1500 - 1000
      'concurrent2': { duration: 700, startTime: 1100, endTime: 1800 }  // 1800 - 1100
    })
  })

  it('should ignore end measurement without start', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    performanceNowSpy.mockReturnValue(1000)
    result.current.endMeasure('non-existent')
    
    expect(result.current.metrics).toEqual({})
  })

  it('should overwrite measurement if started twice', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    // Primeira tentativa
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure('operation')
    })
    
    // Segunda tentativa (deve sobrescrever)
    performanceNowSpy.mockReturnValue(1500)
    act(() => {
      result.current.startMeasure('operation')
    })
    
    // Finaliza
    performanceNowSpy.mockReturnValue(2000)
    act(() => {
      result.current.endMeasure('operation')
    })
    
    expect(result.current.metrics['operation'].duration).toBe(500) // 2000 - 1500
  })

  it('should clear all metrics', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    // Adiciona algumas métricas
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure('operation1')
    })
    
    performanceNowSpy.mockReturnValue(1200)
    act(() => {
      result.current.endMeasure('operation1')
    })
    
    performanceNowSpy.mockReturnValue(1500)
    act(() => {
      result.current.startMeasure('operation2')
    })
    
    performanceNowSpy.mockReturnValue(1800)
    act(() => {
      result.current.endMeasure('operation2')
    })
    
    expect(Object.keys(result.current.metrics)).toHaveLength(2)
    
    // Limpa as métricas
    act(() => {
      result.current.clearMetrics()
    })
    
    expect(result.current.metrics).toEqual({})
  })

  it('should maintain function references across renders', () => {
    const { result, rerender } = renderHook(() => usePerformanceMonitor())
    
    const initialStartMeasure = result.current.startMeasure
    const initialEndMeasure = result.current.endMeasure
    const initialClearMetrics = result.current.clearMetrics
    
    rerender()
    
    expect(result.current.startMeasure).toBe(initialStartMeasure)
    expect(result.current.endMeasure).toBe(initialEndMeasure)
    expect(result.current.clearMetrics).toBe(initialClearMetrics)
  })

  it('should handle measurements with same name sequentially', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    // Primeira medição
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure('repeated-operation')
    })
    
    performanceNowSpy.mockReturnValue(1300)
    act(() => {
      result.current.endMeasure('repeated-operation')
    })
    
    expect(result.current.metrics['repeated-operation'].duration).toBe(300)
    
    // Segunda medição com mesmo nome (deve sobrescrever)
    performanceNowSpy.mockReturnValue(2000)
    act(() => {
      result.current.startMeasure('repeated-operation')
    })
    
    performanceNowSpy.mockReturnValue(2100)
    act(() => {
      result.current.endMeasure('repeated-operation')
    })
    
    expect(result.current.metrics['repeated-operation'].duration).toBe(100)
  })

  it('should handle edge case with zero duration', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure('instant-operation')
    })
    
    // Mesmo tempo de início e fim
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.endMeasure('instant-operation')
    })
    expect(result.current.metrics['instant-operation']).toBeDefined()
    expect(result.current.metrics['instant-operation'].duration).toBe(0)
  })

  it('should handle very small time differences', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    performanceNowSpy.mockReturnValue(1000.123)
    act(() => {
      result.current.startMeasure('micro-operation')
    })
    
    performanceNowSpy.mockReturnValue(1000.456)
    act(() => {
      result.current.endMeasure('micro-operation')
    })
    
    expect(result.current.metrics['micro-operation'].duration).toBeCloseTo(0.333, 3)
  })

  it('should work with special characters in measurement names', () => {
    const { result } = renderHook(() => usePerformanceMonitor())
    
    const specialName = 'operation-with_special.chars@123'
    
    performanceNowSpy.mockReturnValue(1000)
    act(() => {
      result.current.startMeasure(specialName)
    })
    
    performanceNowSpy.mockReturnValue(1500)
    act(() => {
      result.current.endMeasure(specialName)
    })
    
    expect(result.current.metrics[specialName].duration).toBe(500)
  })
})