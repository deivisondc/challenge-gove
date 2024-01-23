export type FileImportStatusType = 'QUEUED' | 'PROCESSING' | 'SUCCESS' | 'WARNING' | 'ERROR'

export type FileImportType = {
  id: number;
  filename: string;
  status: FileImportStatusType;
  created_at: string;
}
