import { renderHook, act, waitFor } from '@testing-library/react'
import { createMockIntersectionObserver } from '../utils'
import { useIntersectionObserver } from '../../utils/hooks-simple';

describe('useIntersectionObserver', () => {
  let mockObserve: jest.Mock
  let mockUnobserve: jest.Mock
  let mockDisconnect: jest.Mock
  let mockIntersectionObserver: jest.Mock

  beforeEach(() => {
    // Cria mocks para os métodos do IntersectionObserver
    mockObserve = jest.fn()
    mockUnobserve = jest.fn()
    mockDisconnect = jest.fn()
    
    // Cria o mock do construtor IntersectionObserver
    mockIntersectionObserver = jest.fn().mockImplementation((callback, options) => ({
      observe: mockObserve,
      unobserve: mockUnobserve,
      disconnect: mockDisconnect
    }))
    
    // Substitui o IntersectionObserver global
    global.IntersectionObserver = mockIntersectionObserver as any
  })

  afterEach(() => {
    jest.clearAllMocks()
  })

  it('should return initial state', () => {
    const { result } = renderHook(() => useIntersectionObserver())
    
    expect(result.current.isIntersecting).toBe(false)
    expect(result.current.entry).toBeNull()
    expect(result.current.ref).toBeDefined()
  })

  it('should create IntersectionObserver when ref is set', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    // Simula a atribuição do ref
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    expect(mockIntersectionObserver).toHaveBeenCalledTimes(1)
    expect(mockIntersectionObserver).toHaveBeenCalledWith(
      expect.any(Function),
      {}
    )
    
    expect(mockObserve).toHaveBeenCalledWith(element)
  })

  it('should use custom options', async () => {
    const options = {
      threshold: 0.5,
      root: document.body,
      rootMargin: '10px'
    }

    const { result, rerender } = renderHook(() => useIntersectionObserver(options))
    
    const element = document.createElement('div')
    document.body.appendChild(element)
    
    act(() => {
      result.current.ref.current = element
    })

    // Força re-renderização para executar o useEffect
    act(() => {
      rerender()
    })

    // Aguarda que o IntersectionObserver seja criado
    await waitFor(() => {
      expect(mockIntersectionObserver).toHaveBeenCalledWith(
        expect.any(Function),
        expect.objectContaining(options)
      )
    })
    
    // Cleanup
    document.body.removeChild(element)
  })

  it('should update state when intersection changes', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    // Obtém a instância mock do observer
    const observerInstance = mockIntersectionObserver.mock.instances[0]
    const callback = mockIntersectionObserver.mock.calls[0][0]

    // Simula entrada na viewport
    const mockEntry = {
      isIntersecting: true,
      target: element,
      intersectionRatio: 0.5,
      boundingClientRect: {} as DOMRectReadOnly,
      intersectionRect: {} as DOMRectReadOnly,
      rootBounds: {} as DOMRectReadOnly,
      time: Date.now()
    }

    act(() => {
      callback([mockEntry], {} as IntersectionObserver)
    })

    expect(result.current.isIntersecting).toBe(true)
    expect(result.current.entry).toEqual(mockEntry)
  })

  it('should handle multiple intersection changes', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    const observerInstance = mockIntersectionObserver.mock.instances[0]
    const callback = mockIntersectionObserver.mock.calls[0][0]

    // Primeira mudança - entra na viewport
    const entryIn = {
      isIntersecting: true,
      target: element,
      intersectionRatio: 0.8,
      boundingClientRect: {} as DOMRectReadOnly,
      intersectionRect: {} as DOMRectReadOnly,
      rootBounds: {} as DOMRectReadOnly,
      time: Date.now()
    }

    act(() => {
      callback([entryIn], {} as IntersectionObserver)
    })
    expect(result.current.isIntersecting).toBe(true)

    // Segunda mudança - sai da viewport
    const entryOut = {
      isIntersecting: false,
      target: element,
      intersectionRatio: 0,
      boundingClientRect: {} as DOMRectReadOnly,
      intersectionRect: {} as DOMRectReadOnly,
      rootBounds: {} as DOMRectReadOnly,
      time: Date.now()
    }

    act(() => {
      callback([entryOut], {} as IntersectionObserver)
    })
    expect(result.current.isIntersecting).toBe(false)
    expect(result.current.entry).toEqual(entryOut)
  })

  it('should observe element when ref is set', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    expect(mockObserve).toHaveBeenCalledWith(element)
  })

  it('should unobserve previous element when ref changes', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    // Primeiro elemento
    const element1 = document.createElement('div')
    act(() => {
      result.current.ref.current = element1
    })
    rerender()

    expect(mockObserve).toHaveBeenCalledWith(element1)

    // Segundo elemento
    const element2 = document.createElement('div')
    act(() => {
      result.current.ref.current = element2
    })
    rerender()

    expect(mockDisconnect).toHaveBeenCalled()
    expect(mockObserve).toHaveBeenCalledWith(element2)
  })

  it('should disconnect observer on unmount', () => {
    const { result, unmount, rerender } = renderHook(() => useIntersectionObserver())
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    unmount()
    
    expect(mockDisconnect).toHaveBeenCalled()
  })

  it('should handle null ref assignment', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    expect(mockObserve).toHaveBeenCalledWith(element)

    // Atribui null ao ref
    act(() => {
      result.current.ref.current = null
    })
    rerender()

    expect(mockDisconnect).toHaveBeenCalled()
  })

  it('should handle null element', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    // Simula elemento sendo removido
    act(() => {
      result.current.ref.current = null
    })
    rerender()

    // Não deve quebrar
    expect(result.current.isIntersecting).toBe(false)
  })

  it('should work with different threshold values', () => {
    const thresholds = [0, 0.25, 0.5, 0.75, 1]
    
    const { result, rerender } = renderHook(() => 
      useIntersectionObserver({ threshold: thresholds })
    )
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    expect(mockIntersectionObserver).toHaveBeenCalledWith(
      expect.any(Function),
      expect.objectContaining({ threshold: thresholds })
    )
  })

  it('should handle rootMargin correctly', () => {
    const rootMargin = '20px 10px'
    
    const { result, rerender } = renderHook(() => 
      useIntersectionObserver({ rootMargin })
    )
    
    const element = document.createElement('div')
    act(() => {
      result.current.ref.current = element
    })
    rerender()

    expect(mockIntersectionObserver).toHaveBeenCalledWith(
      expect.any(Function),
      expect.objectContaining({ rootMargin })
    )
  })

  it('should maintain stable ref callback', () => {
    const { result, rerender } = renderHook(() => useIntersectionObserver())
    
    const firstRef = result.current.ref
    
    rerender()
    
    const secondRef = result.current.ref
    
    expect(firstRef).toBe(secondRef)
  })
})