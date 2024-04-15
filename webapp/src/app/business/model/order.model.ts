export interface IOrder {
  id: number
  date: string
  customer: string
  address1: string
  city: string
  postcode: string
  country: string
  amount: number
  status: string
  deleted: string
  last_modified: string
}

export interface IOrderResponse {
  orders: IOrder[]
  pagination: PaginationMetaData
}

export interface PaginationMetaData{
  currentPage: number,
  numItemsPerPage: number,
  totalItems: number
}


export interface SearchOrderItem {
  search?: string,
  page: number
}
