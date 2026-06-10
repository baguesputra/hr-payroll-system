import { describe, it, expect } from 'vitest'
import { formatCurrency, formatDate } from '@/lib/utils'

describe('formatCurrency', () => {
  it('formats IDR currency correctly', () => {
    expect(formatCurrency(5000000)).toContain('5.000.000')
  })

  it('formats zero correctly', () => {
    expect(formatCurrency(0)).toContain('0')
  })
})

describe('formatDate', () => {
  it('formats date string to Indonesian format', () => {
    const result = formatDate('2024-01-15')
    expect(result).toContain('2024')
  })
})
