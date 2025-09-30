"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Car, CreditCard, Receipt, TrendingUp } from "lucide-react"

const fastagProviders = [
  "ICICI Bank FASTag",
  "HDFC Bank FASTag",
  "Paytm FASTag",
  "Axis Bank FASTag",
  "SBI FASTag",
  "Kotak Bank FASTag",
  "IDFC Bank FASTag",
  "Airtel Payments Bank FASTag",
]

const recentRecharges = [
  {
    id: "FT001",
    tagId: "1234567890123456",
    amount: "$50",
    provider: "ICICI Bank",
    status: "Success",
    date: "2024-01-15",
  },
  {
    id: "FT002",
    tagId: "2345678901234567",
    amount: "$30",
    provider: "HDFC Bank",
    status: "Success",
    date: "2024-01-14",
  },
  { id: "FT003", tagId: "3456789012345678", amount: "$25", provider: "Paytm", status: "Failed", date: "2024-01-13" },
]

export function FastagContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">FASTag Recharge</h1>
        <p className="text-muted-foreground">Recharge your FASTag for seamless toll payments</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total Recharges</CardTitle>
            <Car className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">$8,450</div>
            <p className="text-xs text-muted-foreground">This month</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Transactions</CardTitle>
            <Receipt className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">234</div>
            <p className="text-xs text-muted-foreground">This month</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Success Rate</CardTitle>
            <TrendingUp className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">98.5%</div>
            <p className="text-xs text-muted-foreground">+2.1% from last month</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Avg. Recharge</CardTitle>
            <CreditCard className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">$36</div>
            <p className="text-xs text-muted-foreground">Per transaction</p>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>FASTag Recharge</CardTitle>
            <CardDescription>Recharge your FASTag account</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="fastag-provider">FASTag Provider</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select FASTag provider" />
                </SelectTrigger>
                <SelectContent>
                  {fastagProviders.map((provider) => (
                    <SelectItem key={provider} value={provider.toLowerCase().replace(/\s+/g, "-")}>
                      {provider}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="vehicle-no">Vehicle Number</Label>
              <Input id="vehicle-no" placeholder="Enter vehicle number (e.g., MH01AB1234)" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="fastag-id">FASTag ID</Label>
              <Input id="fastag-id" placeholder="Enter 16-digit FASTag ID" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="recharge-amount">Recharge Amount</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select amount" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="20">$20</SelectItem>
                  <SelectItem value="50">$50</SelectItem>
                  <SelectItem value="100">$100</SelectItem>
                  <SelectItem value="200">$200</SelectItem>
                  <SelectItem value="500">$500</SelectItem>
                  <SelectItem value="custom">Custom Amount</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="mobile">Mobile Number</Label>
              <Input id="mobile" placeholder="Enter mobile number" />
            </div>
            <Button className="w-full">Recharge FASTag</Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Recharges</CardTitle>
            <CardDescription>Your recent FASTag recharge history</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentRecharges.map((recharge) => (
                <div key={recharge.id} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <p className="font-medium">{recharge.tagId.slice(-4).padStart(16, "*")}</p>
                    <p className="text-sm text-muted-foreground">
                      {recharge.provider} • {recharge.date}
                    </p>
                  </div>
                  <div className="text-right">
                    <p className="font-medium">{recharge.amount}</p>
                    <Badge variant={recharge.status === "Success" ? "default" : "destructive"}>{recharge.status}</Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>FASTag Benefits</CardTitle>
          <CardDescription>Why use FASTag for toll payments</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div className="p-4 border rounded-lg">
              <h3 className="font-medium mb-2">Cashless Payments</h3>
              <p className="text-sm text-muted-foreground">No need to carry cash for toll payments</p>
            </div>
            <div className="p-4 border rounded-lg">
              <h3 className="font-medium mb-2">Time Saving</h3>
              <p className="text-sm text-muted-foreground">Quick passage through toll plazas</p>
            </div>
            <div className="p-4 border rounded-lg">
              <h3 className="font-medium mb-2">Fuel Savings</h3>
              <p className="text-sm text-muted-foreground">Reduced waiting time saves fuel</p>
            </div>
            <div className="p-4 border rounded-lg">
              <h3 className="font-medium mb-2">Discounts</h3>
              <p className="text-sm text-muted-foreground">Get discounts on toll charges</p>
            </div>
            <div className="p-4 border rounded-lg">
              <h3 className="font-medium mb-2">Easy Tracking</h3>
              <p className="text-sm text-muted-foreground">Track all your toll transactions</p>
            </div>
            <div className="p-4 border rounded-lg">
              <h3 className="font-medium mb-2">24/7 Service</h3>
              <p className="text-sm text-muted-foreground">Available round the clock</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </motion.div>
  )
}
