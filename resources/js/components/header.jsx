"use client"

import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { usePage } from "@inertiajs/react"

export function Header() {
  const data = usePage();
  const userData = data?.props?.auth?.user;
  return (
    <header className="sticky top-0 z-50 w-full border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="flex h-16 items-center justify-between px-6">
        <div className="flex items-center gap-4">
          <h1 className="text-xl font-semibold text-foreground">Dashboard</h1>
        </div>

        <div className="flex items-center gap-4">
          <Badge variant="outline" className="bg-success/10 text-success border-success/20">
            Credit: {userData?.cbwallet}
          </Badge>
          <Badge variant="outline" className="bg-destructive/10 text-destructive border-destructive/20">
            Debit: {userData?.mainwallet}
          </Badge>
          {userData?.kyc !== 'verified' && (
            <Button variant="outline" size="sm">
              Onboarding
            </Button>
          )}
          <div className="flex items-center gap-3">
            <Avatar className="h-8 w-8">
              <AvatarImage src="/professional-avatar.png" />
              <AvatarFallback>JD</AvatarFallback>
            </Avatar>
            <div className="hidden md:block">
              <p className="text-sm font-medium text-foreground">{userData?.name}</p>
              <p className="text-xs text-muted-foreground">{userData?.role?.name}</p>
            </div>
          </div>
        </div>
      </div>
    </header>
  )
}
