import {
  ModelPropertiesMapper,
  JsonPropertiesMapper,
} from 'jsona'
import {
  IModelPropertiesMapper,
  IJsonPropertiesMapper,
  TAnyKeyValueObject,
  TJsonaModel,
  TJsonaRelationships,
  TJsonaRelationshipBuild,
} from 'jsona/lib/JsonaTypes'
import { defineRelationGetter } from 'jsona/lib/simplePropertyMappers'
import clone from 'lodash/clone'
import get from 'lodash/get'
import { format as dateFormat } from 'date-fns'

import Trigger from '@/lib/triggers/Trigger'
import { TriggerEntityTypes } from '@/lib/triggers/types'
import { RelationInterface } from '@/lib/types'
import { ConditionEntityTypes } from '@/lib/conditions/types'

const RELATIONSHIP_NAMES_PROP = 'relationshipNames'

export class JsonApiModelPropertiesMapper extends ModelPropertiesMapper implements IModelPropertiesMapper {
  getAttributes(model: TJsonaModel): { [index: string]: any } {
    const exceptProps = ['id', '$id', 'type', 'draft', RELATIONSHIP_NAMES_PROP]

    if (
      model.type !== TriggerEntityTypes.AUTOMATIC &&
      model.type !== TriggerEntityTypes.MANUAL
    ) {
      exceptProps.push('triggerId')
    } else if (
      model.type === ConditionEntityTypes.DATE ||
      model.type === ConditionEntityTypes.TIME
    ) {
      exceptProps.push('channel')
      exceptProps.push('device')
      exceptProps.push('property')
      exceptProps.push('operator')
      exceptProps.push('operand')
    }

    if (Array.isArray(model[RELATIONSHIP_NAMES_PROP])) {
      exceptProps.push(...model[RELATIONSHIP_NAMES_PROP])
    }

    const attributes: { [index: string]: any } = {}

    Object.keys(model)
      .forEach((attrName) => {
        if (!exceptProps.includes(attrName)) {
          const kebabName = attrName.replace(/([a-z][A-Z0-9])/g, g => `${g[0]}_${g[1].toLowerCase()}`)

          let jsonAttributes = model[attrName]

          if (attrName === 'days') {
            jsonAttributes = Object.values(jsonAttributes)
          } else if (attrName === 'time' || attrName === 'date') {
            if (jsonAttributes !== null) {
              if (typeof jsonAttributes === 'string') {
                jsonAttributes = dateFormat(new Date(jsonAttributes), 'yyyy-MM-dd\'T\'HH:mm:ssXXXXX')
              } else {
                jsonAttributes = dateFormat(jsonAttributes, 'yyyy-MM-dd\'T\'HH:mm:ssXXXXX')
              }
            }
          } else if (typeof jsonAttributes === 'object' && jsonAttributes !== null) {
            jsonAttributes = {}

            Object.keys(model[attrName]).forEach((subAttrName) => {
              const kebabSubName = subAttrName.replace(/([a-z][A-Z0-9])/g, g => `${g[0]}_${g[1].toLowerCase()}`)

              Object.assign(jsonAttributes, {[kebabSubName]: model[attrName][subAttrName]})
            })
          }

          attributes[kebabName] = jsonAttributes
        }
      })

    return attributes
  }

  getRelationships(model: TJsonaModel): TJsonaRelationships {
    if (
      !Object.prototype.hasOwnProperty.call(model, RELATIONSHIP_NAMES_PROP) ||
      !Array.isArray(model[RELATIONSHIP_NAMES_PROP])
    ) {
      return {}
    }

    const relationshipNames = model[RELATIONSHIP_NAMES_PROP]

    const relationships: { [index: string]: RelationInterface | Array<RelationInterface> } = {}

    relationshipNames
      .forEach((relationName: string) => {
        const kebabName = relationName.replace(/([a-z][A-Z0-9])/g, g => `${g[0]}_${g[1].toLowerCase()}`)

        if (model[relationName] !== undefined) {
          if (Array.isArray(model[relationName])) {
            relationships[kebabName] = model[relationName]
              .map((item: TJsonaModel) => {
                return {
                  id: item.id,
                  type: item.type,
                }
              })
          } else if (typeof model[relationName] === 'object' && model[relationName] !== null) {
            relationships[kebabName] = {
              id: model[relationName].id,
              type: model[relationName].type,
            }
          }
        }
      })

    if (Object.prototype.hasOwnProperty.call(model, 'triggerId')) {
      const trigger = Trigger.find(model.triggerId)

      if (trigger !== null) {
        relationships.trigger = {
          id: trigger.id,
          type: trigger.type,
        }
      }
    }

    return relationships
  }
}

export class JsonApiPropertiesMapper extends JsonPropertiesMapper implements IJsonPropertiesMapper {
  private caseRegExp = '_([a-z0-9])'

  createModel(type: string): TJsonaModel {
    return {type}
  }

  setId(model: TJsonaModel, id: string): void {
    Object.assign(model, {id})
  }

  setAttributes(model: TJsonaModel, attributes: TAnyKeyValueObject): void {
    const regex = new RegExp(this.caseRegExp, 'g')

    Object.keys(attributes).forEach((propName) => {
      const camelName = propName.replace(regex, g => g[1].toUpperCase())

      let modelAttributes = attributes[propName]

      if (typeof modelAttributes === 'object' && modelAttributes !== null) {
        modelAttributes = {}

        Object.keys(attributes[propName]).forEach((subPropName) => {
          const camelSubName = subPropName.replace(regex, g => g[1].toUpperCase())

          Object.assign(modelAttributes, {[camelSubName]: attributes[propName][subPropName]})
        })
      }

      if (propName === 'days') {
        modelAttributes = Object.values(attributes[propName])
      } else if (propName === 'date' || propName === 'time') {
        modelAttributes = (new Date(attributes[propName])).toISOString()
      }

      Object.assign(model, {[camelName]: modelAttributes})
    })

    // Entity received via api is not a draft entity
    Object.assign(model, {draft: false})
  }

  setRelationships(model: TJsonaModel, relationships: TJsonaRelationships): void {
    Object.keys(relationships)
      .forEach((propName) => {
        const regex = new RegExp(this.caseRegExp, 'g')
        const camelName = propName.replace(regex, g => g[1].toUpperCase())

        if (typeof relationships[propName] === 'function') {
          defineRelationGetter(model, propName, <TJsonaRelationshipBuild>relationships[propName])
        } else {
          const relation = clone(relationships[propName])

          if (Array.isArray(relation)) {
            Object.assign(
              model,
              {
                [camelName]: relation.map((item: TJsonaModel) => {
                  let transformed = item

                  transformed = this.transformTrigger(transformed)

                  return transformed
                }),
              },
            )
          } else if (
            get(relation, 'type') === TriggerEntityTypes.AUTOMATIC ||
            get(relation, 'type') === TriggerEntityTypes.MANUAL
          ) {
            Object.assign(model, {triggerId: get(relation, 'id')})
          } else {
            Object.assign(model, {[camelName]: relation})
          }
        }
      })

    const newNames = Object.keys(relationships)
    const currentNames = model[RELATIONSHIP_NAMES_PROP]

    if (currentNames && currentNames.length) {
      Object.assign(model, {[RELATIONSHIP_NAMES_PROP]: [...currentNames, ...newNames].filter((value, i, self) => self.indexOf(value) === i)})
    } else {
      Object.assign(model, {[RELATIONSHIP_NAMES_PROP]: newNames})
    }
  }

  transformTrigger(item: TJsonaModel): TJsonaModel {
    if (Object.prototype.hasOwnProperty.call(item, 'trigger')) {
      Object.assign(item, {triggerId: item.trigger.id})
      Reflect.deleteProperty(item, 'trigger')
    }

    return item
  }
}
