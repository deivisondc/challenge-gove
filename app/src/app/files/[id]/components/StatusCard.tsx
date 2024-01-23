import { cn } from "@/lib/utils"
import { FileImportStatusType } from "@/types/FileImportType"

type StatusCardProps = {
  status: FileImportStatusType
}

const statusDescription: Record<FileImportStatusType, string> = {
  QUEUED: 'File queued to be processed',
  PROCESSING: 'File is being processed',
  SUCCESS: 'File was processed successfully and every row were imported',
  WARNING: 'File was processed successfully but had errors in some rows',
  ERROR: 'File had an error during processing and were not able to read any rows'
}

export default function StatusCard({ status }: StatusCardProps) {
  const capitalizedStatus = status.slice(0, 1) + status.slice(1).toLowerCase()
  let cardClass = 'bg-gray-200 text-gray-800 border-gray-600' 

  if (status === 'PROCESSING') {
    cardClass = 'bg-blue-200 text-blue-800 border-blue-600'
  } else if (status === 'SUCCESS') {
    cardClass = 'bg-green-200 text-green-800 border-green-600'
  } else if (status === 'ERROR') {
    cardClass = 'bg-red-200 text-red-800 border-red-600'
  } else if (status === 'WARNING') {
    cardClass = 'bg-orange-200 text-orange-800 border-orange-600'
  }

  return (
    <div className={cn("w-fit my-2 p-4 rounded-lg border", cardClass)}>
      <p>Status: <strong>{capitalizedStatus}</strong></p>
      <p className="text-sm">{statusDescription[status]}</p>
    </div>
  )
}