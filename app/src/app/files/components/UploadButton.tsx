'use client'

import { Button } from "@/components/ui/button";
import { SymbolIcon, UploadIcon } from "@radix-ui/react-icons";
import { useRef, useState } from "react";

type Status = 'IDLE' | 'UPLOADING'

type UploadButtonProps = {
  onSuccess: () => void
}

const UploadButton = ({ onSuccess }: UploadButtonProps) => {
  const [status, setStatus] = useState<Status>('IDLE');
  const inputRef = useRef<HTMLInputElement>(null);

  const isUploading = status === 'UPLOADING'

  function onUpload() {
    inputRef.current?.click()
  }

  async function onFileChanged() {
    if (inputRef.current && inputRef.current.files) {
      const file = inputRef.current.files[0]

      const formData = new FormData();
      formData.append('file', file)

      setStatus('UPLOADING')
      const rawResponse = await fetch('http://localhost:8000/api/files/import', {
        method: 'POST',
        body: formData
      })

      if (rawResponse.ok) {
        onSuccess()
      } else {
        const response = await rawResponse.json();
        console.error(response)
      }

      setStatus('IDLE')
      inputRef.current.value = ''
    }
  }

  return (
    <>
      <Button
        onClick={onUpload}
        disabled={isUploading}
        className="group"
      >
        {isUploading ? 'Uploading' : 'Upload new file'}
        {isUploading ? (
          <SymbolIcon className="ml-2 spin-in-180 animate-spin" />
        ) : (
          <UploadIcon className="ml-2 group-hover:text-secondary" />
        )}
      </Button>
      <input
        ref={inputRef}
        type="file"
        className="hidden"
        onChange={onFileChanged}
      />
    </>
  );
};

export { UploadButton };