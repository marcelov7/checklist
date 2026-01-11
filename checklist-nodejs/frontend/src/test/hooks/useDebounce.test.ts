import { renderHook, act } from '@testing-library/react'
import { useDebounce } from '../../utils/hooks-simple';

// Mock do timer para controlar o tempo nos testes
jest.useFakeTimers({
  legacyFakeTimers: true
})

describe('useDebounce', () => {
  afterEach(() => {
    jest.clearAllTimers()
  })

  it('should return initial value immediately', () => {
    const { result } = renderHook(() => useDebounce('initial', 500))
    
    expect(result.current).toBe('initial')
  })

  it('should debounce value changes', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useDebounce(value, delay),
      { initialProps: { value: 'initial', delay: 500 } }
    )

    expect(result.current).toBe('initial')

    // Muda o valor
    act(() => {
      rerender({ value: 'updated', delay: 500 })
    })
    
    // Valor ainda deve ser o inicial
    expect(result.current).toBe('initial')

    // Avança o tempo
    act(() => {
      jest.advanceTimersByTime(500)
    })

    // Agora deve ter o valor atualizado
    expect(result.current).toBe('updated')
  })

  it('should reset timer on rapid changes', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useDebounce(value, delay),
      { initialProps: { value: 'initial', delay: 500 } }
    )

    // Primeira mudança
    act(() => {
      rerender({ value: 'first', delay: 500 })
      jest.advanceTimersByTime(250)
    })

    // Segunda mudança antes do timer completar
    act(() => {
      rerender({ value: 'second', delay: 500 })
      jest.advanceTimersByTime(250)
    })

    // Ainda deve ser o valor inicial
    expect(result.current).toBe('initial')

    // Completa o timer (500ms total desde a última mudança)
    act(() => {
      jest.advanceTimersByTime(500)
    })

    // Deve ter o último valor
    expect(result.current).toBe('second')
  })

  it('should handle zero delay', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useDebounce(value, delay),
      { initialProps: { value: 'initial', delay: 0 } }
    )

    act(() => {
      rerender({ value: 'updated', delay: 0 })
    })
    
    act(() => {
      jest.advanceTimersByTime(0)
    })
    
    // Com delay 0, deve atualizar após o timer
    expect(result.current).toBe('updated')
  })

  it('should work with different data types', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useDebounce(value, delay),
      { initialProps: { value: 42, delay: 500 } }
    )

    expect(result.current).toBe(42)

    act(() => {
      rerender({ value: 100, delay: 500 })
    })
    
    act(() => {
      jest.advanceTimersByTime(500)
    })

    expect(result.current).toBe(100)
  })

  it('should cleanup timeout on unmount', () => {
    const clearTimeoutSpy = jest.spyOn(global, 'clearTimeout')
    
    const { unmount, rerender } = renderHook(
      ({ value, delay }) => useDebounce(value, delay),
      { initialProps: { value: 'initial', delay: 500 } }
    )

    act(() => {
      rerender({ value: 'updated', delay: 500 })
    })
    
    act(() => {
      unmount()
    })

    expect(clearTimeoutSpy).toHaveBeenCalled()
    
    clearTimeoutSpy.mockRestore()
  })

  it('should handle dynamically changing delay', () => {
    const { result, rerender } = renderHook(
      ({ value, delay }) => useDebounce(value, delay),
      { initialProps: { value: 'initial', delay: 500 } }
    )

    // Muda valor e delay
    act(() => {
      rerender({ value: 'updated', delay: 1000 })
    })
    
    // Avança menos que o novo delay
    act(() => {
      jest.advanceTimersByTime(500)
    })
    expect(result.current).toBe('initial')

    // Avança o restante do novo delay
    act(() => {
      jest.advanceTimersByTime(500)
    })
    expect(result.current).toBe('updated')
  })
})