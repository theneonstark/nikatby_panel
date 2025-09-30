"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Badge } from "@/components/ui/badge"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { CreditCard, Users, Banknote, Receipt } from "lucide-react"

const cmsServices = [
  { title: "Cash Deposit", description: "Deposit cash to Airtel accounts", icon: Banknote, count: "1,234" },
  { title: "Cash Withdrawal", description: "Withdraw cash from Airtel accounts", icon: CreditCard, count: "856" },
  { title: "Money Transfer", description: "Transfer money between accounts", icon: Users, count: "432" },
  { title: "Bill Payments", description: "Pay various bills through CMS", icon: Receipt, count: "298" },
]

const recentTransactions = [
  { id: "CMS001", type: "Cash Deposit", amount: "$250", customer: "John Doe", status: "Completed", date: "2024-01-15" },
  {
    id: "CMS002",
    type: "Money Transfer",
    amount: "$150",
    customer: "Jane Smith",
    status: "Completed",
    date: "2024-01-14",
  },
  {
    id: "CMS003",
    type: "Cash Withdrawal",
    amount: "$300",
    customer: "Mike Johnson",
    status: "Pending",
    date: "2024-01-13",
  },
  {
    id: "CMS004",
    type: "Bill Payment",
    amount: "$75",
    customer: "Sarah Wilson",
    status: "Completed",
    date: "2024-01-12",
  },
]

export function CMSAirtelContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">CMS-Airtel Services</h1>
        <p className="text-muted-foreground">Cash Management Services and Airtel banking operations</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {cmsServices.map((service, index) => (
          <motion.div
            key={service.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <Card className="hover:shadow-lg transition-shadow">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">{service.title}</CardTitle>
                <service.icon className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{service.count}</div>
                <p className="text-xs text-muted-foreground">{service.description}</p>
              </CardContent>
            </Card>
          </motion.div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>CMS Operations</CardTitle>
            <CardDescription>Perform cash management operations</CardDescription>
          </CardHeader>
          <CardContent>
            <Tabs defaultValue="deposit" className="w-full">
              <TabsList className="grid w-full grid-cols-3">
                <TabsTrigger value="deposit">Deposit</TabsTrigger>
                <TabsTrigger value="withdraw">Withdraw</TabsTrigger>
                <TabsTrigger value="transfer">Transfer</TabsTrigger>
              </TabsList>
              <TabsContent value="deposit" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="deposit-mobile">Mobile Number</Label>
                  <Input id="deposit-mobile" placeholder="Enter mobile number" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="deposit-amount">Amount</Label>
                  <Input id="deposit-amount" placeholder="Enter amount to deposit" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="deposit-pin">Transaction PIN</Label>
                  <Input id="deposit-pin" type="password" placeholder="Enter PIN" />
                </div>
                <Button className="w-full">Process Deposit</Button>
              </TabsContent>
              <TabsContent value="withdraw" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="withdraw-mobile">Mobile Number</Label>
                  <Input id="withdraw-mobile" placeholder="Enter mobile number" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="withdraw-amount">Amount</Label>
                  <Input id="withdraw-amount" placeholder="Enter amount to withdraw" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="withdraw-pin">Transaction PIN</Label>
                  <Input id="withdraw-pin" type="password" placeholder="Enter PIN" />
                </div>
                <Button className="w-full">Process Withdrawal</Button>
              </TabsContent>
              <TabsContent value="transfer" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="from-mobile">From Mobile</Label>
                  <Input id="from-mobile" placeholder="Sender mobile number" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="to-mobile">To Mobile</Label>
                  <Input id="to-mobile" placeholder="Receiver mobile number" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="transfer-amount">Amount</Label>
                  <Input id="transfer-amount" placeholder="Enter amount to transfer" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="transfer-pin">Transaction PIN</Label>
                  <Input id="transfer-pin" type="password" placeholder="Enter PIN" />
                </div>
                <Button className="w-full">Process Transfer</Button>
              </TabsContent>
            </Tabs>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Transactions</CardTitle>
            <CardDescription>Latest CMS transactions</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentTransactions.map((transaction) => (
                <div key={transaction.id} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <p className="font-medium">{transaction.id}</p>
                    <p className="text-sm text-muted-foreground">
                      {transaction.type} • {transaction.customer}
                    </p>
                    <p className="text-xs text-muted-foreground">{transaction.date}</p>
                  </div>
                  <div className="text-right">
                    <p className="font-medium">{transaction.amount}</p>
                    <Badge variant={transaction.status === "Completed" ? "default" : "secondary"}>
                      {transaction.status}
                    </Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </motion.div>
  )
}
