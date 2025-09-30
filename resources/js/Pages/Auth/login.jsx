import { LoginForm } from "@/components/login-form";

export default function LoginPage() {
    return (
        <div className="flex min-h-screen w-full flex-col items-center justify-center p-6 md:p-10">
            <div className="max-w-sm md:max-w-3xl">
                <LoginForm />
            </div>
        </div>
    );
}
