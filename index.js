import express from 'express'; 
import jwt from 'jsonwebtoken';
import mongoose from 'mongoose';
import { registerValidation } from './validations/auth.js';
import { validationResult } from 'express-validator';
import UserModel from './models/User.js';
import bcrypt from 'bcrypt';

mongoose
.connect('mongodb+srv://islomkhon1243:islomkhon1243@cluster0.alcnfvk.mongodb.net/futurum?retryWrites=true&w=majority')
.then(() => console.log('DB Ok'))
.catch((err) => console.log('DB error', err));

const app = express();

app.use(express.json());

app.post('/auth/register', registerValidation, async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json(errors.array());        
        }

        const password = req.body.password;
        const salt = await bcrypt.genSalt(10);
        const passwordHash = await bcrypt.hash(password, salt);

        const doc = new UserModel({
            email: req.body.email,
            fullName: req.body.fullName,
            avatarUrl: req.body.avatarUrl,
            passwordHash,
        });

        const user = await doc.save();

        const token = jwt.sign({
            _id: user._id,
        },
        'secret123',
        {
            expiresIn: '30d',
        },
        );

        res.json({
            ... user._doc,
            token,
        }); 
    } catch (err) {
        console.log(err);
        res.status(500).json({
            message: 'Не удалось зарегистрироваться',
        });
    }
});

app.listen(3000, err => {
    if (err) {
        return console.log(err);
    }

    console.log('Server OK');
});