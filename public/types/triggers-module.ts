import { Database, Model } from '@vuex-orm/core'

export interface GlobalConfigInterface {
  database: Database
  originName?: string
}

export interface ComponentsInterface {
  Model: typeof Model
}

declare module '@vuex-orm/core' {
  // eslint-disable-next-line @typescript-eslint/no-namespace
  namespace Model {
    // Exchange origin name
    const $triggersModuleOrigin: string
  }
}

// Re-export models types
export * from '@/lib/types'
export * from '@/lib/models/actions/types'
export * from '@/lib/models/conditions/types'
export * from '@/lib/models/notifications/types'
export * from '@/lib/models/triggers/types'
