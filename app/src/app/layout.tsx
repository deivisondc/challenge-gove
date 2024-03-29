import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import { MainNav } from "@/components/MainNav";
import Image from "next/image";

import GoveLogo from '@/assets/gove.png'

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "Gove - Challenge",
  description: "Technical challenge promoted by Gove.digital",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body className={inter.className}>
        <header className="flex h-16 px-8 items-center border-b bg-primary gap-8">
          <Image
            src={GoveLogo}
            alt="Gove Logo"
            width={80}
          />
          <MainNav className="mx-6" />
        </header>

        <div className="flex justify-center">
          <main className="flex flex-col flex-1 px-4 md:px-12 py-8 overflow-auto max-w-[1280px]">
            {children}
          </main>
        </div>
      </body>
    </html>
  );
}
