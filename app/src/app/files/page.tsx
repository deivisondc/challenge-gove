'use client'

import { PageTitle } from "@/components/PageTitle";
import { UploadButton } from "./components/UploadButton";
import { TemplateFileButton } from "./components/TemplateFileButton";
import FileImportsTable from "./components/FileImportsTable";
import { useCallback, useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { ResponseType } from "@/types/ResponseType";
import { FileImportType } from "@/types/FileImportType";

export default function Files() {
  const { push } = useRouter()
  const [response, setResponse] = useState<ResponseType<FileImportType>>()

  const fetchFiles = useCallback(async (page = 1) => {
    const dataRaw = await fetch(`http://localhost:8000/api/files?page=${page}`);
    const data = (await dataRaw.json()) as ResponseType<FileImportType>

    setResponse(data)
  }, [])

  useEffect(() => {
    fetchFiles()
  }, [fetchFiles])


  function onRowClick(itemId: number) {
    push(`/files/${itemId}`)
  }

  return (
    <>
      <div className="flex justify-between items-start flex-col sm:flex-row gap-2">
        <PageTitle>Files</PageTitle>

        <div className="flex flex-row-reverse sm:flex-row items-center gap-4">
          <TemplateFileButton />
          <UploadButton onSuccess={fetchFiles} />
        </div>
      </div>

      {response ? (
          <FileImportsTable onRowClick={onRowClick} fetchFiles={fetchFiles} {...response} />
      ) : 'Loading'}

    </>
  );
};