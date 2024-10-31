export interface QuotationService {
    serviceId: number;
    isApproved: boolean;
  }
  
  export interface CreateQuotationRequest {
    carId: number;
    services: QuotationService[];
    status: boolean;
    approvedDateClient: string;
  }
  
  export interface CreateQuotationResponse {
    success: boolean;
    message: string;
    data?: any; // Adjust this as needed based on the response data structure
  }
  