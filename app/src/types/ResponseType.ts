type NavigationButtonType = {
  label: string
  active: boolean
}

export type ResponseType<T> = {
  data: Array<T>
  from: number
  to: number
  total: number
  current_page: number
  last_page: number
  links: Array<NavigationButtonType>
}
