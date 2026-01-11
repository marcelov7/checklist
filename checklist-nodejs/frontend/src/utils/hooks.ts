import { useMemo, useCallback, useRef, useEffect, useState } from 'react';

// ===== HOOKS DE PERFORMANCE =====

/**
 * Hook para computação pesada memoizada
 */
export const useHeavyComputation = <T>(
  computeFn: () => T,
  dependencies: React.DependencyList
): T => {
  return useMemo(computeFn, dependencies);
};

/**
 * Hook para debounce de valores
 */
export const useDebounce = <T>(value: T, delay: number): T => {
  const [debouncedValue, setDebouncedValue] = useState<T>(value);

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedValue(value);
    }, delay);

    return () => {
      clearTimeout(handler);
    };
  }, [value, delay]);

  return debouncedValue;
};

/**
 * Hook para throttle de valores
 */
export const useThrottle = <T>(value: T, delay: number): T => {
  const [throttledValue, setThrottledValue] = useState<T>(value);
  const lastExecutionTime = useRef<number>(0);
  const timeoutRef = useRef<NodeJS.Timeout | null>(null);
  const lastValue = useRef<T>(value);
  const pendingValue = useRef<T>(value);
  const isFirstChange = useRef<boolean>(true);

  // Detecta mudança de valor diretamente
  const hasValueChanged = lastValue.current !== value;
  
  if (hasValueChanged) {
    lastValue.current = value;
    pendingValue.current = value;
    
    // Limpa timeout anterior se existir
    if (timeoutRef.current) {
      clearTimeout(timeoutRef.current);
      timeoutRef.current = null;
    }

    const now = Date.now();
    const timeSinceLastExecution = now - lastExecutionTime.current;

    // Primeira mudança ou tempo suficiente passou
    if (isFirstChange.current || timeSinceLastExecution >= delay) {
      setThrottledValue(value);
      lastExecutionTime.current = now;
      isFirstChange.current = false;
    } else {
      // Precisa aguardar - usa pendingValue.current para pegar o valor mais recente
      const remainingTime = delay - timeSinceLastExecution;
      timeoutRef.current = setTimeout(() => {
        setThrottledValue(pendingValue.current);
        lastExecutionTime.current = Date.now();
        timeoutRef.current = null;
      }, remainingTime);
    }
  }

  // Cleanup no unmount
  useEffect(() => {
    return () => {
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
        timeoutRef.current = null;
      }
    };
  }, []);

  return throttledValue;
};

/**
 * Hook para Intersection Observer
 */
export const useIntersectionObserver = (
  options: IntersectionObserverInit = {}
): {
  isIntersecting: boolean;
  entry: IntersectionObserverEntry | null;
  ref: React.RefCallback<HTMLElement>;
} => {
  const [isIntersecting, setIsIntersecting] = useState(false);
  const [entry, setEntry] = useState<IntersectionObserverEntry | null>(null);
  const observerRef = useRef<IntersectionObserver | null>(null);
  const elementRef = useRef<HTMLElement | null>(null);

  const ref = useCallback((node: HTMLElement | null) => {
    // Cleanup previous observer
    if (observerRef.current) {
      observerRef.current.disconnect();
      observerRef.current = null;
    }

    elementRef.current = node;

    if (node) {
      const observer = new IntersectionObserver(
        ([entry]) => {
          setIsIntersecting(entry.isIntersecting);
          setEntry(entry);
        },
        options
      );

      observerRef.current = observer;
      observer.observe(node);
    }
  }, [options]);

  useEffect(() => {
    return () => {
      if (observerRef.current) {
        observerRef.current.disconnect();
      }
    };
  }, []);

  return { isIntersecting, entry, ref };
};

/**
 * Hook para lista virtual
 */
export const useVirtualList = <T>(
  items: T[],
  itemHeight: number,
  containerHeight: number,
  overscan: number = 5
) => {
  const [scrollTop, setScrollTop] = useState(0);

  const startIndex = Math.max(0, Math.floor(scrollTop / itemHeight) - overscan);
  const endIndex = Math.min(
    items.length - 1,
    Math.ceil((scrollTop + containerHeight) / itemHeight) + overscan
  );

  const visibleItems = items.slice(startIndex, endIndex + 1);
  const totalHeight = items.length * itemHeight;
  const offsetY = startIndex * itemHeight;

  const handleScroll = useCallback((e: React.UIEvent<HTMLDivElement>) => {
    setScrollTop(e.currentTarget.scrollTop);
  }, []);

  return {
    visibleItems,
    totalHeight,
    offsetY,
    handleScroll,
    startIndex,
    endIndex,
  };
};

/**
 * Hook para monitoramento de performance
 */
export const usePerformanceMonitor = () => {
  const [metrics, setMetrics] = useState<Record<string, {
    duration: number;
    startTime: number;
    endTime: number;
  }>>({});
  
  const startTimes = useRef<Record<string, number>>({});

  const startMeasure = useCallback((name: string) => {
    const now = performance?.now ? performance.now() : Date.now();
    startTimes.current[name] = now;
  }, []);

  const endMeasure = useCallback((name: string) => {
    const startTime = startTimes.current[name];
    if (startTime === undefined) return;

    const endTime = performance?.now ? performance.now() : Date.now();
    const duration = endTime - startTime;

    setMetrics(prev => ({
      ...prev,
      [name]: {
        duration,
        startTime,
        endTime
      }
    }));

    delete startTimes.current[name];
  }, []);

  const clearMetrics = useCallback(() => {
    setMetrics({});
    startTimes.current = {};
  }, []);

  return {
    metrics,
    startMeasure,
    endMeasure,
    clearMetrics
  };
};

/**
 * Hook para cache de dados
 */
export const useCachedData = <T>(
  key: string,
  fetchFn: () => Promise<T>,
  ttl: number = 5 * 60 * 1000 // 5 minutos
) => {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);
  const cache = useRef<Map<string, { data: T; timestamp: number }>>(new Map());

  const fetchData = useCallback(async () => {
    const cached = cache.current.get(key);
    const now = Date.now();

    if (cached && now - cached.timestamp < ttl) {
      setData(cached.data);
      return;
    }

    setLoading(true);
    setError(null);

    try {
      const result = await fetchFn();
      cache.current.set(key, { data: result, timestamp: now });
      setData(result);
    } catch (err) {
      setError(err as Error);
    } finally {
      setLoading(false);
    }
  }, [key, fetchFn, ttl]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  const invalidate = useCallback(() => {
    cache.current.delete(key);
    fetchData();
  }, [key, fetchData]);

  return { data, loading, error, refetch: fetchData, invalidate };
};