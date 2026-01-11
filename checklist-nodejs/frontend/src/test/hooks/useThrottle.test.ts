import { renderHook } from '../utils'
import { useThrottle } from '../../utils/hooks-simple';

describe('useThrottle', () => {
  afterEach(() => {
    jest.clearAllTimers()
  })

  it('should return initial value immediately', () => {
    const { result } = renderHook(() => useThrottle('initial', 500))
    
    expect(result.current).toBe('initial')
  })

  it('should throttle value changes', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: 'initial', delay: 500 } }
    )

    expect(result.current).toBe('initial')

    // Primeira mudança - deve ser aplicada imediatamente
    rerender({ value: 'first', delay: 500 })
    expect(result.current).toBe('first')

    // Segunda mudança - deve ser ignorada (throttled)
    rerender({ value: 'second', delay: 500 })
    expect(result.current).toBe('first')

    // Avança o tempo para liberar o throttle
    jest.advanceTimersByTime(500)

    // Terceira mudança - deve ser aplicada
    rerender({ value: 'third', delay: 500 })
    expect(result.current).toBe('third')
  })

  it('should handle rapid changes correctly', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: 'initial', delay: 1000 } }
    )

    // Primeira mudança
    rerender({ value: 'change1', delay: 1000 })
    expect(result.current).toBe('change1')

    // Mudanças rápidas - devem ser ignoradas
    rerender({ value: 'change2', delay: 1000 })
    rerender({ value: 'change3', delay: 1000 })
    rerender({ value: 'change4', delay: 1000 })
    
    expect(result.current).toBe('change1')

    // Avança metade do tempo
    jest.advanceTimersByTime(500)
    
    // Ainda deve ser throttled
    rerender({ value: 'change5', delay: 1000 })
    expect(result.current).toBe('change1')

    // Completa o período de throttle
    jest.advanceTimersByTime(500)

    // Agora uma nova mudança deve ser aceita
    rerender({ value: 'final', delay: 1000 })
    expect(result.current).toBe('final')
  })

  it('should work with zero delay', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: 'initial', delay: 0 } }
    )

    // Com delay 0, todas as mudanças devem ser aplicadas
    rerender({ value: 'first', delay: 0 })
    expect(result.current).toBe('first')

    rerender({ value: 'second', delay: 0 })
    expect(result.current).toBe('second')

    rerender({ value: 'third', delay: 0 })
    expect(result.current).toBe('third')
  })

  it('should work with different data types', () => {
    // Teste com números
    const { result, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: 0, delay: 500 } }
    )

    expect(result.current).toBe(0) // Valor inicial

    rerender({ value: 42, delay: 500 })
    expect(result.current).toBe(42) // Primeira mudança aplicada imediatamente

    rerender({ value: 100, delay: 500 })
    expect(result.current).toBe(42) // Throttled

    jest.advanceTimersByTime(500)
    
    rerender({ value: 200, delay: 500 })
    expect(result.current).toBe(200)
  })

  it('should handle dynamically changing delay', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: 'initial', delay: 1000 } }
    )

    expect(result.current).toBe('initial') // Valor inicial

    // Primeira mudança
    rerender({ value: 'first', delay: 1000 })
    expect(result.current).toBe('first') // Primeira mudança aplicada imediatamente

    // Muda o delay para um valor menor
    rerender({ value: 'second', delay: 200 })
    expect(result.current).toBe('first') // Ainda throttled

    // Avança pelo delay menor
    jest.advanceTimersByTime(200)

    // Nova mudança deve ser aceita
    rerender({ value: 'third', delay: 200 })
    expect(result.current).toBe('third')
  })

  it('should cleanup timeout on unmount', () => {
    const clearTimeoutSpy = jest.spyOn(global, 'clearTimeout')
    
    const { unmount, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: 'initial', delay: 500 } }
    )

    rerender({ value: 'changed', delay: 500 })
    unmount()

    expect(clearTimeoutSpy).toHaveBeenCalled()
    
    clearTimeoutSpy.mockRestore()
  })

  it('should handle object values correctly', () => {
    const initialObj = { id: 1, name: 'initial' }
    const firstObj = { id: 2, name: 'first' }
    const secondObj = { id: 3, name: 'second' }

    const { result, rerender } = renderHook(
      ({ value, delay }) => useThrottle(value, delay),
      { initialProps: { value: initialObj, delay: 500 } }
    )

    expect(result.current).toEqual(initialObj)

    // Primeira mudança
    rerender({ value: firstObj, delay: 500 })
    expect(result.current).toEqual(firstObj)

    // Segunda mudança - throttled
    rerender({ value: secondObj, delay: 500 })
    expect(result.current).toEqual(firstObj)

    // Avança o tempo
    jest.advanceTimersByTime(500)

    // Nova mudança
    const thirdObj = { id: 4, name: 'third' }
    rerender({ value: thirdObj, delay: 500 })
    expect(result.current).toEqual(thirdObj)
  })
})