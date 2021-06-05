import { Database, Model } from '@vuex-orm/core'
import { Plugin } from '@vuex-orm/core/dist/src/plugins/use'

export interface InstallFunction extends Plugin {
}

export interface GlobalConfigInterface {
  database: Database
  originName?: string
}

export interface ComponentsInterface {
  Model: typeof Model
}

declare module '@vuex-orm/core' {
  namespace Model {
    // Exchange origin name
    const $triggersModuleOrigin: string
  }
}

// Re-export models types
export * from '@/lib/types'
export * from '@/lib/actions/types'
export * from '@/lib/conditions/types'
export * from '@/lib/notifications/types'
export * from '@/lib/triggers/types'
